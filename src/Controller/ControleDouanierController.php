<?php

namespace App\Controller;

use App\Entity\ControleDouanier;
use App\Form\ControleDouanierType;
use App\Repository\ControleDouanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        if ($searchTerm || $status !== null && $status !== 'all' || $dateFrom || $dateTo) {
            $controleDouaniers = $controleDouanierRepository->findBySearchCriteria($searchTerm, $status, $dateFrom, $dateTo);
        } else {
            $controleDouaniers = $controleDouanierRepository->findAll();
        }
        
        return $this->render('controle_douanier/index.html.twig', [
            'controle_douaniers' => $controleDouaniers,
            'searchTerm' => $searchTerm,
            'selectedStatus' => $status,
            'dateFrom' => $dateFrom ? $dateFrom->format('Y-m-d') : null,
            'dateTo' => $dateTo ? $dateTo->format('Y-m-d') : null,
        ]);
    }

    #[Route('/new', name: 'app_controle_douanier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $controleDouanier = new ControleDouanier();
        $form = $this->createForm(ControleDouanierType::class, $controleDouanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($controleDouanier);
            $entityManager->flush();

            $this->addFlash('success', 'Contrôle douanier créé avec succès');
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
            $entityManager->flush();

            $this->addFlash('success', 'Contrôle douanier modifié avec succès');
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
        if ($this->isCsrfTokenValid('delete'.$controleDouanier->getId_controle(), $request->request->get('_token'))) {
            $entityManager->remove($controleDouanier);
            $entityManager->flush();

            $this->addFlash('success', 'Contrôle douanier supprimé avec succès');
        }

        return $this->redirectToRoute('app_controle_douanier_index', [], Response::HTTP_SEE_OTHER);
    }
}