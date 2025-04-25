<?php

namespace App\Controller;

use App\Entity\Facturisation;
use App\Form\FacturisationType;
use App\Repository\FacturisationRepository;
use App\Service\PDFGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/facturisation')]
class FacturisationController extends AbstractController
{
    #[Route(name: 'app_facturisation_index', methods: ['GET'])]
    public function index(Request $request, FacturisationRepository $repo, PaginatorInterface $paginator): Response
    {
        $query = $repo->createQueryBuilder('f')->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('facturisation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_facturisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $facturisation = new Facturisation();
        $form = $this->createForm(FacturisationType::class, $facturisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($facturisation);
            $em->flush();
            return $this->redirectToRoute('app_facturisation_index');
        }

        return $this->render('facturisation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_facturisation_show', methods: ['GET'])]
    public function show(Facturisation $facturisation): Response
    {
        return $this->render('facturisation/show.html.twig', [
            'facturisation' => $facturisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facturisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facturisation $facturisation, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FacturisationType::class, $facturisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_facturisation_index');
        }

        return $this->render('facturisation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_facturisation_delete', methods: ['POST'])]
    public function delete(Request $request, Facturisation $facturisation, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facturisation->getId(), $request->get('_token'))) {
            $em->remove($facturisation);
            $em->flush();
        }

        return $this->redirectToRoute('app_facturisation_index');
    }

    #[Route('/{id}/pdf', name: 'app_facturisation_pdf', methods: ['GET'])]
    public function generatePDF(Facturisation $facturisation, PDFGeneratorService $pdfGenerator): Response
    {
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/facture_' . $facturisation->getId() . '.pdf';

        $clientEmail = 'rami@yahoo.fr'; // à remplacer par le vrai email du client si dispo

        $pdfGenerator->generateFacturisationPDF(
            $pdfPath,
            $facturisation->getPaymentMethod(),
            $facturisation->getAmount(),
            $facturisation->getPaymentDate()->format('Y-m-d'),
            $facturisation->getStatus(),
            $clientEmail
        );

        return new BinaryFileResponse($pdfPath);
    }
}
