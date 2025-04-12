<?php

namespace App\Controller;

use App\Entity\ControleDouanier;
use App\Form\ControleDouanierPublicType;
use App\Repository\ControleDouanierRepository;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/douane')]
final class ControleDouanierPublicController extends AbstractController
{
    #[Route('/', name: 'app_douane_index', methods: ['GET'])]
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
        if ($searchTerm || $status !== null && $status !== 'all' || $dateFrom || $dateTo) {
            $controleDouaniers = $controleDouanierRepository->findBySearchCriteria($searchTerm, $status, $dateFrom, $dateTo);
        } else {
            // Pour le front office, on pourrait vouloir limiter le nombre de résultats
            $controleDouaniers = $controleDouanierRepository->findBy([], ['date_controle' => 'DESC'], 10);
        }
        
        // Récupérer les statistiques pour le front office
        $stats = [
            'total' => count($controleDouanierRepository->findAll()),
            'enAttente' => count($controleDouanierRepository->findBy(['statut' => 'En attente'])),
            'enCours' => count($controleDouanierRepository->findBy(['statut' => 'En cours'])),
            'valides' => count($controleDouanierRepository->findBy(['statut' => 'Validé'])),
            'rejetes' => count($controleDouanierRepository->findBy(['statut' => 'Rejeté']))
        ];
        
        return $this->render('front/douane/index.html.twig', [
            'controle_douaniers' => $controleDouaniers,
            'searchTerm' => $searchTerm,
            'selectedStatus' => $status,
            'dateFrom' => $dateFrom ? $dateFrom->format('Y-m-d') : null,
            'dateTo' => $dateTo ? $dateTo->format('Y-m-d') : null,
            'stats' => $stats
        ]);
    }

    #[Route('/details/{id_controle}', name: 'app_douane_details', methods: ['GET'])]
    public function details(ControleDouanier $controleDouanier): Response
    {
        return $this->render('front/douane/details.html.twig', [
            'controle_douanier' => $controleDouanier,
        ]);
    }

    #[Route('/suivi', name: 'app_douane_suivi', methods: ['GET', 'POST'])]
    public function suivi(Request $request, ControleDouanierRepository $repository, LivraisonRepository $livraisonRepository): Response
    {
        $reference = $request->request->get('reference');
        $result = null;
        
        if ($reference) {
            // Recherche par ID du contrôle
            $result = $repository->findOneBy(['id_controle' => $reference]);
            
            // Si aucun résultat, essayer de trouver par ID de livraison
            if (!$result) {
                $livraison = $livraisonRepository->find($reference);
                if ($livraison) {
                    // Récupérer le premier contrôle douanier associé à cette livraison
                    $controlesByLivraison = $repository->findByLivraison($livraison);
                    $result = !empty($controlesByLivraison) ? $controlesByLivraison[0] : null;
                }
            }
        }
        
        return $this->render('front/douane/suivi.html.twig', [
            'result' => $result,
            'reference' => $reference
        ]);
    }

    #[Route('/demande', name: 'app_douane_demande', methods: ['GET', 'POST'])]
    public function demande(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Uniquement accessible aux utilisateurs connectés dans un vrai cas d'usage
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $controleDouanier = new ControleDouanier();
        // Par défaut, le statut est "En attente" pour les demandes du front office
        $controleDouanier->setStatut('En attente');
        
        $form = $this->createForm(ControleDouanierPublicType::class, $controleDouanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($controleDouanier);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande de contrôle douanier a été enregistrée avec succès.');
            return $this->redirectToRoute('app_douane_confirmation', [
                'id' => $controleDouanier->getIdControle()
            ]);
        }

        return $this->render('front/douane/demande.html.twig', [
            'controle_douanier' => $controleDouanier,
            'form' => $form,
        ]);
    }

    #[Route('/confirmation/{id}', name: 'app_douane_confirmation', methods: ['GET'])]
    public function confirmation(ControleDouanier $controleDouanier): Response
    {
        return $this->render('front/douane/confirmation.html.twig', [
            'controle_douanier' => $controleDouanier,
        ]);
    }
    

    #[Route('/faq', name: 'app_douane_faq', methods: ['GET'])]
    public function faq(): Response
    {
        return $this->render('front/douane/faq.html.twig');
    }
}