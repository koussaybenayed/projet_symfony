<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Form\LivraisonTrackingType;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/front/livraison', name: 'front_livraison_')]
class FrontLivraisonController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $status = $request->query->get('status');
        
        if ($status) {
            $livraisons = $livraisonRepository->findBy(
                ['destination_status' => $status],
                ['created_at' => 'DESC']
            );
        } else {
            $livraisons = $livraisonRepository->findBy([], ['created_at' => 'DESC']);
        }

        return $this->render('front/livraison/index.html.twig', [
            'livraisons' => $livraisons,
            'current_filter' => $status
        ]);
    }

    #[Route('/ajout', name: 'ajout', methods: ['GET', 'POST'])]
    public function ajout(Request $request, EntityManagerInterface $em): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraison->setDestinationStatus('En attente');
            
            $em->persist($livraison);
            $em->flush();

            $this->addFlash('success', 'Votre livraison Liv-#'.$livraison->getIdLivraisons().' a été créée avec succès !');
            return $this->redirectToRoute('front_livraison_detail', [
                'id_livraisons' => $livraison->getIdLivraisons()
            ]);
        }

        return $this->render('front/livraison/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tracking', name: 'tracking', methods: ['GET', 'POST'])]
    public function tracking(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonTrackingType::class);
        $form->handleRequest($request);
        
        $livraison = null;
        $submitted = false;
        
        if ($form->isSubmitted() && $form->isValid()) {
            $submitted = true;
            $data = $form->getData();
            $trackingNumber = $data['tracking_number'];
            
            // Vérification du format Liv-#XXXX
            if (preg_match('/^Liv-#(\d+)$/', $trackingNumber, $matches)) {
                $idLivraison = $matches[1];
                $livraison = $livraisonRepository->find($idLivraison);
            } else {
                $this->addFlash('error', 'Format de numéro de suivi invalide. Le format doit être Liv-#XXXX');
            }
        }
        
        return $this->render('front/livraison/tracking.html.twig', [
            'form' => $form->createView(),
            'livraison' => $livraison,
            'submitted' => $submitted,
        ]);
    }
    
    #[Route('/{id_livraisons}', name: 'detail', methods: ['GET'], requirements: ['id_livraisons' => '\d+'])]
    public function detail(Livraison $livraison): Response
    {
        if ($livraison->getDestinationStatus() === 'Annulé') {
            $this->addFlash('error', 'Cette livraison a été annulée.');
            return $this->redirectToRoute('front_livraison_index');
        }

        return $this->render('front/livraison/detail.html.twig', [
            'livraison' => $livraison,
        ]);
    }
}