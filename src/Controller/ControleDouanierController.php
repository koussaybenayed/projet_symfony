<?php

namespace App\Controller;

use App\Entity\ControleDouanier;
use App\Form\ControleDouanierType;
use App\Repository\ControleDouanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/controle/douanier')]
class ControleDouanierController extends AbstractController
{
    #[Route('/', name: 'app_controle_douanier_index', methods: ['GET'])]
    public function index(Request $request, ControleDouanierRepository $controleDouanierRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $status = $request->query->get('status');
        $dateFrom = $request->query->get('dateFrom') ? new \DateTime($request->query->get('dateFrom')) : null;
        $dateTo = $request->query->get('dateTo') ? new \DateTime($request->query->get('dateTo')) : null;

        if ($searchTerm || ($status && $status !== 'all') || $dateFrom || $dateTo) {
            $controleDouaniers = $controleDouanierRepository->findBySearchCriteria($searchTerm, $status, $dateFrom, $dateTo);
            $this->addFlash('info', sprintf('%d contrôles trouvés avec vos critères de recherche', count($controleDouaniers)));
        } else {
            $controleDouaniers = $controleDouanierRepository->findAll();
        }

        $statistics = $this->getStatistics($controleDouaniers);
        // Modification ici - compter tous les contrôles en attente, pas seulement ceux des 2 prochains jours
        $pendingControlsCount = $statistics['En attente'];

        return $this->render('controle_douanier/index.html.twig', [
            'controle_douaniers' => $controleDouaniers,
            'searchTerm' => $searchTerm,
            'selectedStatus' => $status,
            'dateFrom' => $dateFrom ? $dateFrom->format('Y-m-d') : null,
            'dateTo' => $dateTo ? $dateTo->format('Y-m-d') : null,
            'statistics' => $statistics,
            'pendingControlsCount' => $pendingControlsCount,
        ]);
    }

    #[Route('/calendrier', name: 'app_controle_douanier_calendar', methods: ['GET'])]
    public function calendar(ControleDouanierRepository $repository): Response
    {
        $countControles = count($repository->findBySearchCriteria(null, 'En attente', null, null));
        
        return $this->render('controle_douanier/calendar.html.twig', [
            'countControles' => $countControles
        ]);
    }

    #[Route('/api/calendar/events', name: 'app_calendar_events', methods: ['GET'])]
    public function getCalendarEvents(Request $request, ControleDouanierRepository $repository): JsonResponse
    {
        try {
            $start = new \DateTime($request->query->get('start', 'first day of this month'));
            $end = new \DateTime($request->query->get('end', 'last day of this month'));
            $status = $request->query->get('status', 'En attente');
        } catch (\Exception $e) {
            $start = new \DateTime('first day of this month');
            $end = new \DateTime('last day of this month');
            $status = 'En attente';
        }

        // Récupérer seulement les contrôles "En attente"
        $controles = $repository->findBetweenDatesByStatus($start, $end, $status);
        
        $events = [];
        foreach ($controles as $controle) {
            if (!$controle->getDateControle()) {
                continue;
            }

            $dateControle = clone $controle->getDateControle();
            $endDate = clone $dateControle;
            $endDate->modify('+1 day');

            $events[] = [
                'id' => $controle->getIdControle(),
                'title' => sprintf('Contrôle #%d - %s', $controle->getIdControle(), $controle->getPaysDouane()),
                'start' => $dateControle->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'color' => $this->getStatusColor($controle->getStatut()),
                'textColor' => '#ffffff',
                'allDay' => true,
                'url' => $this->generateUrl('app_controle_douanier_show', ['id_controle' => $controle->getIdControle()]),
                'extendedProps' => [
                    'status' => $controle->getStatut(),
                    'country' => $controle->getPaysDouane(),
                    'comments' => $controle->getCommentaires() ?? 'Aucun commentaire'
                ]
            ];
        }

        return $this->json($events);
    }

    #[Route('/new', name: 'app_controle_douanier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $controleDouanier = new ControleDouanier();
        $form = $this->createForm(ControleDouanierType::class, $controleDouanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country = $controleDouanier->getPaysDouane();
            $coordinates = $this->fetchCoordinates($country);

            if ($coordinates) {
                $controleDouanier->setLatitude($coordinates['latitude']);
                $controleDouanier->setLongitude($coordinates['longitude']);
                $this->addFlash('success', sprintf('Coordonnées GPS de %s récupérées avec succès (Lat: %s, Long: %s)', $country, $coordinates['latitude'], $coordinates['longitude']));
            } else {
                $this->addFlash('warning', 'Impossible de récupérer les coordonnées GPS pour ce pays');
            }

            $entityManager->persist($controleDouanier);
            $entityManager->flush();

            $this->addFlash('success', sprintf('Contrôle douanier #%d pour %s créé avec succès', $controleDouanier->getIdControle(), $controleDouanier->getPaysDouane()));
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
            if ($form->get('pays_douane')->getData() !== $controleDouanier->getPaysDouane()) {
                $coordinates = $this->fetchCoordinates($controleDouanier->getPaysDouane());
                if ($coordinates) {
                    $controleDouanier->setLatitude($coordinates['latitude']);
                    $controleDouanier->setLongitude($coordinates['longitude']);
                    $this->addFlash('success', 'Coordonnées GPS mises à jour');
                }
            }

            $entityManager->flush();

            $this->addFlash('success', sprintf('Contrôle douanier #%d pour %s modifié avec succès', $controleDouanier->getIdControle(), $controleDouanier->getPaysDouane()));
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

            $this->addFlash('success', sprintf('Contrôle douanier #%d pour %s supprimé avec succès', $controleDouanier->getIdControle(), $controleDouanier->getPaysDouane()));
        } else {
            $this->addFlash('error', 'Token CSRF invalide, suppression annulée');
        }

        return $this->redirectToRoute('app_controle_douanier_index', [], Response::HTTP_SEE_OTHER);
    }

    private function fetchCoordinates(string $country): ?array
    {
        $client = HttpClient::create();
        $url = sprintf('https://geocoding-api.open-meteo.com/v1/search?name=%s&count=1&language=fr&format=json', urlencode($country));

        try {
            $response = $client->request('GET', $url);
            $data = $response->toArray();

            if (isset($data['results'][0])) {
                return [
                    'latitude' => $data['results'][0]['latitude'],
                    'longitude' => $data['results'][0]['longitude'],
                ];
            }
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Erreur lors de la récupération des coordonnées GPS: '.$e->getMessage());
        }

        return null;
    }

    private function getStatistics(array $controleDouaniers): array
    {
        $statistics = [
            'En attente' => 0,
            'En cours' => 0,
            'Validé' => 0,
            'Rejeté' => 0,
        ];

        foreach ($controleDouaniers as $controle) {
            $status = $controle->getStatut();
            if (array_key_exists($status, $statistics)) {
                $statistics[$status]++;
            }
        }

        return $statistics;
    }

    private function checkPendingControls(array $controleDouaniers): array
    {
        $today = new \DateTime();
        $tomorrow = (new \DateTime())->modify('+1 day');
        $alerts = [];

        foreach ($controleDouaniers as $controle) {
            if ($controle->getStatut() === 'En attente' &&
                $controle->getDateControle() &&
                ($controle->getDateControle()->format('Y-m-d') === $today->format('Y-m-d') ||
                 $controle->getDateControle()->format('Y-m-d') === $tomorrow->format('Y-m-d'))) {
                $alerts[] = $controle;
            }
        }

        return $alerts;
    }

    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'En attente' => '#ffc107', // Jaune
            'En cours' => '#17a2b8',   // Bleu
            'Validé' => '#28a745',     // Vert
            'Rejeté' => '#dc3545',     // Rouge
            default => '#6c757d'       // Gris
        };
    }
}