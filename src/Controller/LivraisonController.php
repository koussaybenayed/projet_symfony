<?php

// src/Controller/LivraisonController.php
namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/livraison')]
class LivraisonController extends AbstractController
{
    private const WEATHER_API_KEY = '56af7bb2f3e84733a83135244252502';
    private const SHIPPO_API_KEY = 'shippo_test_a678789b0079fd59bfa10d34b02734de9d6ffdf5';
    private const SHIPPO_CARRIERS_URL = 'https://api.goshippo.com/carrier_accounts/';
    
    private $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(
        Request $request,
        LivraisonRepository $livraisonRepository,
        PaginatorInterface $paginator
    ): Response {
        $searchTerm = $request->query->get('search');
        $status = $request->query->get('status', 'all');
        
        $queryBuilder = $livraisonRepository->createQueryBuilder('l')
            ->orderBy('l.created_at', 'DESC');

        if ($status !== 'all') {
            $queryBuilder->andWhere('l.destination_status = :status')
                ->setParameter('status', $status);
        }

        if ($searchTerm) {
            $queryBuilder->andWhere('
                l.transporteur LIKE :search OR 
                l.destination_status LIKE :search OR 
                l.id_livraisons LIKE :search
            ')->setParameter('search', '%'.$searchTerm.'%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('livraison/index.html.twig', [
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
            'selectedStatus' => $status
        ]);
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $livraison->setCreatedAt(new \DateTime());
                
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();
                        
            $this->addFlash('success', 'Livraison créée avec succès');
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }
                
        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/meteo-info', name: 'app_livraison_weather', methods: ['GET'])]
    public function weatherPage(): Response
    {
        return $this->render('livraison/weather.html.twig');
    }

    #[Route('/transporteurs-liste', name: 'app_livraison_carriers', methods: ['GET'])]
    public function carriersPage(): Response
    {
        return $this->render('livraison/carriers.html.twig');
    }
    
    #[Route('/{id_livraisons}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    #[Route('/{id_livraisons}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Livraison $livraison, 
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
                        
            $this->addFlash('success', 'Livraison modifiée avec succès');
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }
                
        return $this->render('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id_livraisons}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Livraison $livraison, 
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId_livraisons(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
                        
            $this->addFlash('success', 'Livraison supprimée avec succès');
        }
                
        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/api/weather', name: 'app_livraison_fetch_weather', methods: ['POST'])]
    public function fetchWeather(Request $request): JsonResponse
    {
        $city = $request->request->get('city');
        
        if (!$city) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez entrer une ville'
            ]);
        }
        
        try {
            $apiUrl = "http://api.weatherapi.com/v1/current.json?key=" . self::WEATHER_API_KEY . "&q=" . urlencode($city) . "&aqi=no";
            $response = $this->httpClient->request('GET', $apiUrl);
            
            if ($response->getStatusCode() != 200) {
                throw new \Exception('Erreur de connexion à l\'API météo, code réponse : ' . $response->getStatusCode());
            }
            
            $data = $response->toArray();
            
            if (!isset($data['current'])) {
                throw new \Exception('Données météo non disponibles pour la ville : ' . $city);
            }
            
            return new JsonResponse([
                'success' => true,
                'weather' => [
                    'temperature' => $data['current']['temp_c'],
                    'humidity' => $data['current']['humidity'],
                    'wind_speed' => $data['current']['wind_kph'],
                    'condition' => $data['current']['condition']['text'],
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    #[Route('/api/transporteurs', name: 'app_livraison_fetch_carriers', methods: ['GET'])]
    public function fetchCarriers(): JsonResponse
    {
        try {
            $response = $this->httpClient->request('GET', self::SHIPPO_CARRIERS_URL, [
                'headers' => [
                    'Authorization' => 'ShippoToken ' . self::SHIPPO_API_KEY,
                    'Content-Type' => 'application/json'
                ]
            ]);
            
            if ($response->getStatusCode() != 200) {
                throw new \Exception('Erreur de connexion à l\'API Shippo, code réponse : ' . $response->getStatusCode());
            }
            
            $data = $response->toArray();
            
            if (!isset($data['results']) || empty($data['results'])) {
                throw new \Exception('Aucun transporteur disponible.');
            }
            
            $carriers = [];
            foreach ($data['results'] as $carrier) {
                if (isset($carrier['carrier'])) {
                    $carriers[] = [
                        'name' => $carrier['carrier'],
                        'id' => $carrier['object_id'] ?? null,
                    ];
                }
            }
            
            return new JsonResponse([
                'success' => true,
                'carriers' => $carriers
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    #[Route('/export/csv', name: 'app_livraison_export_csv', methods: ['GET'])]
    public function exportCsv(LivraisonRepository $livraisonRepository): Response
    {
        $livraisons = $livraisonRepository->findAll();
        
        $csvContent = "Date de Livraison Estimée,Coût de Livraison,Date de Création,Poids du Colis,Statut de Destination,Transporteur\n";
        
        foreach ($livraisons as $livraison) {
            $csvContent .= $livraison->getEstimatedDelivery()->format('Y-m-d') . ",";
            $csvContent .= $livraison->getDeliveryCost() . ",";
            $csvContent .= $livraison->getCreatedAt()->format('Y-m-d') . ",";
            $csvContent .= $livraison->getPoidsColis() . ",";
            $csvContent .= $livraison->getDestinationStatus() . ",";
            $csvContent .= ($livraison->getTransporteur() ?? 'N/A') . "\n";
        }
        
        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="livraisons.csv"');
        
        return $response;
    }
}