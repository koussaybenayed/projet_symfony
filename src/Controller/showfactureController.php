<?php

namespace App\Controller;

use App\Repository\FacturisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/showfacture')]
final class showfactureController extends AbstractController
{
    #[Route(name: 'app_facturisation_front_index', methods: ['GET'])]
    public function index(FacturisationRepository $facturisationRepository): Response
    {
        return $this->render('facturisation/front/index.html.twig', [
            'facturisations' => $facturisationRepository->findAll(),
        ]);
    }
}
