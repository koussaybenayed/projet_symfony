<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Form\BilletType;
use App\Repository\BilletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/billet')]
final class BilletController extends AbstractController
{
    #[Route(name: 'app_billet_index', methods: ['GET'])]
    public function index(BilletRepository $billetRepository): Response
    {
        return $this->render('billet/index.html.twig', [
            'billets' => $billetRepository->findAll(),
            'stats' => $billetRepository->countBilletsByDestination(), // Ajoutez cette ligne
        'totalBillets' => $billetRepository->count([]) // Et cette ligne
    
        ]);
    }

    #[Route('/new', name: 'app_billet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $billet = new Billet();
        $form = $this->createForm(BilletType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($billet);
            $entityManager->flush();

            return $this->redirectToRoute('app_billet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('billet/new.html.twig', [
            'billet' => $billet,
            'form' => $form,
        ]);
    }
    #[Route('/statistiques', name: 'app_billet_statistiques', methods: ['GET'])]
public function statistiques(BilletRepository $billetRepository): Response
{
    $stats = $billetRepository->countBilletsByDestination();


    return $this->render('billet/statistiques.html.twig', [
        'stats' => $stats,
    ]);
}
#[Route('/favori/toggle/{id}', name: 'toggle_billet_favori', methods: ['POST'])]
public function toggleBilletFavori(Billet $billet, SessionInterface $session): RedirectResponse
{
    $favoris = $session->get('favoris_billets', []);  // Récupère la liste des favoris dans la session

    if (in_array($billet->getId(), $favoris)) {
        // Si le billet est déjà dans les favoris, on le retire
        $favoris = array_diff($favoris, [$billet->getId()]);
    } else {
        // Sinon, on l'ajoute
        $favoris[] = $billet->getId();
    }

    $session->set('favoris_billets', $favoris);  // Sauvegarde la nouvelle liste dans la session

    return $this->redirectToRoute('front_billet_list');  // Redirige vers la liste des billets
}


#[Route('/billet/favoris', name: 'app_billet_favoris', methods: ['GET', 'POST'])]
public function afficherFavoris(SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    $favoris = $session->get('favoris_billets', []);  // Récupère les favoris
    $billetsFavoris = !empty($favoris)
        ? $entityManager->getRepository(Billet::class)->findBy(['id' => $favoris])  // Récupère les billets favoris depuis la base de données
        : [];

    return $this->render('billet/favoris.html.twig', [
        'billetsFavoris' => $billetsFavoris,
    ]);
}


    #[Route('/{id}', name: 'app_billet_show', methods: ['GET'])]
    public function show(Billet $billet): Response
    {
        return $this->render('billet/show.html.twig', [
            'billet' => $billet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_billet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Billet $billet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BilletType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_billet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('billet/edit.html.twig', [
            'billet' => $billet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_billet_delete', methods: ['POST'])]
    public function delete(Request $request, Billet $billet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($billet);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_billet_index', [], Response::HTTP_SEE_OTHER);
    }
    
    




}