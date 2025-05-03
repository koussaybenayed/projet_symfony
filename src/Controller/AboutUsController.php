<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{
    #[Route('/about-us', name: 'app_about_us')]
    public function index(): Response
    {
        $teamMembers = [
            ['initial' => 'L', 'name' => 'Lina', 'role' => 'Gestion Livraisons'],
            ['initial' => 'M', 'name' => 'Mariem', 'role' => 'Gestion Facturisation'],
            ['initial' => 'S', 'name' => 'Skander', 'role' => 'Gestion Reclamation'],
            ['initial' => 'B', 'name' => 'Balkiss', 'role' => 'Gestion Billets'],
            ['initial' => 'K', 'name' => 'Koussay', 'role' => 'Gestionnaire User']
        ];

        return $this->render('aboutus/index.html.twig', [
            'team_members' => $teamMembers,
            'contact_email' => 'Titans@transportapp.com',
            'phone_number' => '+216 12 345 678',
            'company_address' => 'Rue Ariana soghra, Tunis, Tunisie'
        ]);
    }
}