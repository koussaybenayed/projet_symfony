<?php

namespace App\Controller;

use App\Entity\Response as EntityResponse;
use App\Form\ResponseType;
use App\Repository\ResponseRepository;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/response')]
final class ResponseController extends AbstractController
{
    #[Route(name: 'app_response_index', methods: ['GET'])]
    public function index(
        Request $request,
        ResponseRepository $responseRepository, 
        ReclamationRepository $reclamationRepository
    ): HttpResponse {
        $searchTerm = $request->query->get('search');
        
        $responses = $searchTerm 
            ? $responseRepository->findBySearchTerm($searchTerm)
            : $responseRepository->findAll();

        return $this->render('response/index.html.twig', [
            'responses' => $responses,
            'reclamations' => $reclamationRepository->findAll(),
        ]);

    }

    #[Route('/new', name: 'app_response_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): HttpResponse
    {
        $response = new EntityResponse();
        $form = $this->createForm(ResponseType::class, $response);
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
    public function show(EntityResponse $response): HttpResponse
    {
        return $this->render('response/show.html.twig', [
            'response' => $response,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_response_pdf', methods: ['GET'])]
    public function pdf(EntityResponse $response): HttpResponse
    {
        // Configure Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($pdfOptions);
        
        // Generate HTML for PDF
        $html = $this->renderView('response/pdf.html.twig', [
            'response' => $response
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Output PDF
        $output = $dompdf->output();
        
        return new HttpResponse(
            $output,
            HttpResponse::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="response_'.$response->getId().'.pdf"'
            ]
        );
    }

    #[Route('/{id}/edit', name: 'app_response_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityResponse $response, EntityManagerInterface $entityManager): HttpResponse
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
    public function delete(Request $request, EntityResponse $response, EntityManagerInterface $entityManager): HttpResponse
    {
        if ($this->isCsrfTokenValid('delete'.$response->getId(), $request->get('_token'))) {
            $entityManager->remove($response);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_response_index', [], HttpResponse::HTTP_SEE_OTHER);
    }
}