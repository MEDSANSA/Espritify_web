<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{

    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    #[Route('/meetings', name: 'app_meetings')]
    public function meetings(): Response
    {
        return $this->render('accueil/meetings.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    #[Route('/meeting-details', name: 'app_meeting-details')]
    public function meeting_details(): Response
    {
        return $this->render('accueil/meeting-details.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    #[Route('/test', name: 'testEntity')]
    public function testEntity(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render("accueil/test.html.twig", ['list' => $utilisateurRepository->findAll()]);
    }
}
