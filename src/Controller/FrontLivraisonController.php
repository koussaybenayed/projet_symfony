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
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        $livraisons = $livraisonRepository->findBy(
            ['destination_status' => [' En_cours', 'En attente', 'livré ','Annulé']],
            ['created_at' => 'DESC']
        );

        return $this->render('front/livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

   // src/Controller/FrontLivraisonController.php

#[Route('/ajout', name: 'ajout', methods: ['GET', 'POST'])]
public function ajout(Request $request, EntityManagerInterface $em): Response
{
    $livraison = new Livraison();
    $form = $this->createForm(LivraisonType::class, $livraison);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Générer un numéro de suivi personnalisé
        $livraison->setDestinationStatus('pending');
        
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
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $trackingNumber = $data['tracking_number'];
            $idLivraison = intval(str_replace('Liv-#', '', $trackingNumber));
            $livraison = $livraisonRepository->find($idLivraison);
        }
        
        return $this->render('front/livraison/tracking.html.twig', [
            'form' => $form->createView(),
            'livraison' => $livraison,
            'submitted' => $form->isSubmitted(),
        ]);
    }
    

    #[Route('/{id_livraisons}', name: 'detail', methods: ['GET'], requirements: ['id_livraisons' => '\d+'])]
    public function detail(Livraison $livraison): Response
    {
        if ($livraison->getDestinationStatus() === 'cancelled') {
            $this->addFlash('error', 'Cette livraison a été annulée.');
            return $this->redirectToRoute('front_livraison_index');
        }

        return $this->render('front/livraison/detail.html.twig', [
            'livraison' => $livraison,
        ]);
    }
}
