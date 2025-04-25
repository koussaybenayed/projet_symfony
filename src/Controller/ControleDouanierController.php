<?php

namespace App\Controller;

use App\Entity\ControleDouanier;
use App\Form\ControleDouanierType;
use App\Repository\ControleDouanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/controle/douanier')]
final class ControleDouanierController extends AbstractController
{
    #[Route('/', name: 'app_controle_douanier_index', methods: ['GET'])]
    public function index(Request $request, ControleDouanierRepository $controleDouanierRepository): Response
    {
        // Récupérer les paramètres de recherche
        $searchTerm = $request->query->get('search');
        $status = $request->query->get('status');
        $dateFrom = null;
        $dateTo = null;

        // Conversion des dates si présentes
        if ($request->query->has('dateFrom') && !empty($request->query->get('dateFrom'))) {
            $dateFrom = new \DateTime($request->query->get('dateFrom'));
        }

        if ($request->query->has('dateTo') && !empty($request->query->get('dateTo'))) {
            $dateTo = new \DateTime($request->query->get('dateTo'));
        }

        // Effectuer la recherche si des critères sont spécifiés
        if ($searchTerm || ($status !== null && $status !== 'all') || $dateFrom || $dateTo) {
            $controleDouaniers = $controleDouanierRepository->findBySearchCriteria($searchTerm, $status, $dateFrom, $dateTo);
            $this->addFlash('info', sprintf(
                '%d contrôles trouvés avec vos critères de recherche',
                count($controleDouaniers)
            ));
        } else {
            $controleDouaniers = $controleDouanierRepository->findAll();
        }

        // Statistiques pour le graphique
        $statistics = $this->getStatistics($controleDouaniers);

        // Vérification des contrôles en attente
        $pendingAlerts = $this->checkPendingControls($controleDouaniers);
        $pendingControlsCount = count($pendingAlerts); // Ajout de cette ligne

        return $this->render('controle_douanier/index.html.twig', [
            'controle_douaniers' => $controleDouaniers,
            'searchTerm' => $searchTerm,
            'selectedStatus' => $status,
            'dateFrom' => $dateFrom ? $dateFrom->format('Y-m-d') : null,
            'dateTo' => $dateTo ? $dateTo->format('Y-m-d') : null,
            'statistics' => $statistics,
            'pendingAlerts' => $pendingAlerts,
            'pendingControlsCount' => $pendingControlsCount, // Ajout de la variable ici
        ]);
    }

    #[Route('/new', name: 'app_controle_douanier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $controleDouanier = new ControleDouanier();
        $form = $this->createForm(ControleDouanierType::class, $controleDouanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des coordonnées géographiques
            $country = $controleDouanier->getPaysDouane();
            $coordinates = $this->fetchCoordinates($country);

            if ($coordinates) {
                $controleDouanier->setLatitude($coordinates['latitude']);
                $controleDouanier->setLongitude($coordinates['longitude']);
                $this->addFlash('success', sprintf(
                    'Coordonnées GPS de %s récupérées avec succès (Lat: %s, Long: %s)',
                    $country,
                    $coordinates['latitude'],
                    $coordinates['longitude']
                ));
            } else {
                $this->addFlash('warning', 'Impossible de récupérer les coordonnées GPS pour ce pays');
            }

            $entityManager->persist($controleDouanier);
            $entityManager->flush();

            $this->addFlash('success', sprintf(
                'Contrôle douanier #%d pour %s créé avec succès',
                $controleDouanier->getIdControle(),
                $controleDouanier->getPaysDouane()
            ));
            return $this->redirectToRoute('app_controle_douanier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('controle_douanier/new.html.twig', [
            'controle_douanier' => $controleDouanier,
            'form' => $form,
        ]);
    }

    #[Route('/{id_controle}', name: 'app_controle_douanier_show', methods: ['GET'])]
    public function show(ControleDouanier $controleDouanier): Response
    {
        $this->addFlash('info', sprintf(
            'Consultation du contrôle douanier #%d - Statut: %s',
            $controleDouanier->getIdControle(),
            $controleDouanier->getStatut()
        ));

        return $this->render('controle_douanier/show.html.twig', [
            'controle_douanier' => $controleDouanier,
        ]);
    }

    #[Route('/{id_controle}/edit', name: 'app_controle_douanier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ControleDouanier $controleDouanier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ControleDouanierType::class, $controleDouanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour des coordonnées si le pays a changé
            if ($form->get('pays_douane')->getData() !== $controleDouanier->getPaysDouane()) {
                $coordinates = $this->fetchCoordinates($controleDouanier->getPaysDouane());
                if ($coordinates) {
                    $controleDouanier->setLatitude($coordinates['latitude']);
                    $controleDouanier->setLongitude($coordinates['longitude']);
                    $this->addFlash('success', 'Coordonnées GPS mises à jour');
                }
            }

            $entityManager->flush();

            $this->addFlash('success', sprintf(
                'Contrôle douanier #%d pour %s modifié avec succès',
                $controleDouanier->getIdControle(),
                $controleDouanier->getPaysDouane()
            ));
            return $this->redirectToRoute('app_controle_douanier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('controle_douanier/edit.html.twig', [
            'controle_douanier' => $controleDouanier,
            'form' => $form,
        ]);
    }

    #[Route('/{id_controle}', name: 'app_controle_douanier_delete', methods: ['POST'])]
    public function delete(Request $request, ControleDouanier $controleDouanier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$controleDouanier->getIdControle(), $request->request->get('_token'))) {
            $entityManager->remove($controleDouanier);
            $entityManager->flush();

            $this->addFlash('success', sprintf(
                'Contrôle douanier #%d pour %s supprimé avec succès',
                $controleDouanier->getIdControle(),
                $controleDouanier->getPaysDouane()
            ));
        } else {
            $this->addFlash('error', 'Token CSRF invalide, suppression annulée');
        }

        return $this->redirectToRoute('app_controle_douanier_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Récupère les coordonnées géographiques d'un pays via l'API Open-Meteo
     */
    private function fetchCoordinates(string $country): ?array
    {
        $client = HttpClient::create();
        $url = sprintf('https://geocoding-api.open-meteo.com/v1/search?name=%s&count=1&language=fr&format=json',
            urlencode($country));

        try {
            $response = $client->request('GET', $url);
            $data = $response->toArray();

            if (isset($data['results'][0])) {
                return [
                    'latitude' => $data['results'][0]['latitude'],
                    'longitude' => $data['results'][0]['longitude']
                ];
            }
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Erreur lors de la récupération des coordonnées GPS');
        }

        return null;
    }

    /**
     * Génère les statistiques pour le graphique
     */
    private function getStatistics(array $controleDouaniers): array
    {
        $statistics = [
            'En attente' => 0,
            'En cours' => 0,
            'Validé' => 0,
            'Rejeté' => 0
        ];

        foreach ($controleDouaniers as $controle) {
            $status = $controle->getStatut();
            if (array_key_exists($status, $statistics)) {
                $statistics[$status]++;
            }
        }

        return $statistics;
    }

    /**
     * Vérifie les contrôles en attente pour aujourd'hui et demain
     */
    private function checkPendingControls(array $controleDouaniers): array
    {
        $today = new \DateTime();
        $tomorrow = (new \DateTime())->modify('+1 day');
        $alerts = [];

        foreach ($controleDouaniers as $controle) {
            if ($controle->getStatut() === 'En attente' &&
                ($controle->getDateControle()->format('Y-m-d') === $today->format('Y-m-d') ||
                 $controle->getDateControle()->format('Y-m-d') === $tomorrow->format('Y-m-d'))) {
                $alerts[] = $controle;
            }
        }

        return $alerts;
    }
}