<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BilletRepository;


class FrontController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'title' => 'Agen | Bootstrap Agency Template',
        ]);
    }


    #[Route('/billets', name: 'front_billet_list', methods: ['GET'])]
    public function listeBillets(BilletRepository $billetRepository): Response
    {
        $billets = $billetRepository->findAll();

        return $this->render('front/listeBillet.html.twig', [
            'billets' => $billets,
        ]);
    }
}