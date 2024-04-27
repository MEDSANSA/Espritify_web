<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('basefront.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    #[Route('/acceuil', name: 'app_acceuil')]
    public function acceuil(): Response
    {
        return $this->render('Front/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    
}
