<?php

namespace App\Controller;

use App\Entity\Response as EntityResponse;  // Alias the entity Response
use App\Form\ResponseType;
use App\Repository\ResponseRepository;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;  // Alias the Symfony Response
use Symfony\Component\Routing\Attribute\Route;

#[Route('/response')]
final class ResponseController extends AbstractController
{
    #[Route(name: 'app_response_index', methods: ['GET'])]
    public function index(ResponseRepository $responseRepository, 
    ReclamationRepository $reclamationRepository): HttpResponse
    {
        return $this->render('response/index.html.twig', [
            'responses' => $responseRepository->findAll(),
            'reclamations' => $reclamationRepository->findAll(),//
        ]);
    }

    #[Route('/new', name: 'app_response_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): HttpResponse
    {
        $response = new EntityResponse();  // Use aliased entity
        $form = $this->createForm(ResponseType::class, $response,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('app_response_index', [], HttpResponse::HTTP_SEE_OTHER);
        }

        return $this->render('response/new.html.twig', [
            'response' => $response,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_response_show', methods: ['GET'])]
    public function show(EntityResponse $response): HttpResponse  // Use aliased entity
    {
        return $this->render('response/show.html.twig', [
            'response' => $response,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_response_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityResponse $response, EntityManagerInterface $entityManager): HttpResponse  // Use aliased entity
    {
        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_response_index', [], HttpResponse::HTTP_SEE_OTHER);
        }

        return $this->render('response/edit.html.twig', [
            'response' => $response,
            'form' => $form,
        ]);
    }

        #[Route('/{id}', name: 'app_response_delete', methods: ['POST'])]
        public function delete(Request $request, EntityResponse $response, EntityManagerInterface $entityManager): HttpResponse  // Use aliased entity
        {
            if ($this->isCsrfTokenValid('delete'.$response->getId(), $request->get('_token'))) {
                $entityManager->remove($response);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_response_index', [], HttpResponse::HTTP_SEE_OTHER);
        }
}
