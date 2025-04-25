<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/aboutus', name: 'about_us')]
    public function aboutUs(): Response
    {
        return $this->render('aboutus/aboutus.html.twig');
    }
}