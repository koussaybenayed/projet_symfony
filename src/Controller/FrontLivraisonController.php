<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonTrackingType;
use App\Repository\LivraisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/front/livraison', name: 'front_livraison_')]
class FrontLivraisonController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        // ✅ Récupérer les livraisons avec un statut valide, triées par date de création
        $livraisons = $livraisonRepository->findBy(
            ['destination_status' => ['pending', 'in_progress', 'delivered']],
            ['created_at' => 'DESC'] // ✅ Utilise bien le nom exact de la propriété Doctrine
        );

        return $this->render('front/livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

    #[Route('/tracking', name: 'tracking', methods: ['GET', 'POST'])]
    public function tracking(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonTrackingType::class);
        $form->handleRequest($request);
        
        $livraison = null;
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $trackingNumber = $data['tracking_number'];

            // ✅ Extraire l'ID depuis le numéro de suivi de type "Liv-#123"
            $idLivraison = intval(str_replace('Liv-#', '', $trackingNumber));
            $livraison = $livraisonRepository->find($idLivraison);
        }
        
        return $this->render('front/livraison/tracking.html.twig', [
            'form' => $form->createView(),
            'livraison' => $livraison,
            'submitted' => $form->isSubmitted(),
        ]);
    }

    #[Route('/{id_livraisons}', name: 'detail', methods: ['GET'])]
    public function detail(Livraison $livraison): Response
    {
        // ✅ Empêcher l’accès aux livraisons annulées
        if ($livraison->getDestinationStatus() === 'cancelled') {
            $this->addFlash('error', 'Cette livraison a été annulée.');
            return $this->redirectToRoute('front_livraison_index');
        }

        return $this->render('front/livraison/detail.html.twig', [
            'livraison' => $livraison,
        ]);
    }
}