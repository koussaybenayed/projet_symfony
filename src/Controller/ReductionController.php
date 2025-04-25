<?php

namespace App\Controller;

use App\Entity\Reduction;
use App\Form\ReductionType;
use App\Repository\ReductionRepository;
use App\Service\TwilioService; // Importer le service Twilio
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reduction')]
final class ReductionController extends AbstractController
{
    private TwilioService $twilioService; // Déclaration du service Twilio

    public function __construct(TwilioService $twilioService) // Injection du service Twilio
    {
        $this->twilioService = $twilioService;
    }

    #[Route(name: 'app_reduction_index', methods: ['GET'])]
    public function index(Request $request, ReductionRepository $reductionRepository): Response
    {
        // Récupérer le terme de recherche depuis les paramètres GET
        $search = $request->query->get('search');

        // Si un terme de recherche est présent, filtrer les réductions par code
        if ($search) {
            $reductions = $reductionRepository->findByCode($search);
        } else {
            $reductions = $reductionRepository->findAll();
        }

        return $this->render('reduction/index.html.twig', [
            'reductions' => $reductions,
        ]);
    }

    #[Route('/new', name: 'app_reduction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reduction = new Reduction();
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reduction);
            $entityManager->flush();

            // Envoi d'un SMS après la création de la réduction
            $this->twilioService->sendSms('+21693596423', 'Une nouvelle réduction a été créée !');

            return $this->redirectToRoute('app_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reduction/new.html.twig', [
            'reduction' => $reduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reduction_show', methods: ['GET'])]
    public function show(Reduction $reduction): Response
    {
        return $this->render('reduction/show.html.twig', [
            'reduction' => $reduction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reduction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reduction $reduction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reduction/edit.html.twig', [
            'reduction' => $reduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reduction_delete', methods: ['POST'])]
    public function delete(Request $request, Reduction $reduction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reduction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reduction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reduction_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/send-sms/{id}', name: 'send_sms', methods: ['GET'])]
    public function sendSms(int $id, ReductionRepository $reductionRepository): Response
    {
        $reduction = $reductionRepository->find($id);

        if (!$reduction) {
            throw $this->createNotFoundException('Réduction non trouvée');
        }

        // Envoyer un SMS avec les détails de la réduction
        $this->twilioService->sendSms('+21693596423', "Bonjour cher client,\n\nNous sommes ravis de vous informer que vous avez reçu un code de réduction sur votre billet. Cette offre est notre façon de vous remercier d'avoir choisi NaviFly pour vos voyages. Nous apprécions votre confiance et espérons que vous profiterez pleinement de nos services.\n\nMerci encore et bon vol avec NaviFly !\n\nVotre code de réduction est : " . $reduction->getCode());

        return new Response('SMS envoyé pour la réduction : ' . $reduction->getCode());
    }
}