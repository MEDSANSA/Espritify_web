<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Evenement;
use App\Repository\UtilisateurRepository;
use App\Repository\ClubRepository;
use App\Repository\EvenementRepository;
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
    #[Route('/club-details', name: 'app_club_details')]
       public function club_details(ClubRepository $clubRepository): Response
       {
       $clubs =  $clubRepository->findAll();
           return $this->render('accueil/club/club.html.twig', [
               'controller_name' => 'AccueilController',
               'clubs' => $clubs,
           ]);
       }

    #[Route('/evenements-details/{id}' , name : 'app_evenements_details')]
    public function evenement_details(EvenementRepository $evenementsRepository, $id): Response
    {  $club =  $this->getDoctrine()->getRepository(Club::class)->find($id);
        $clubs =  $this->getDoctrine()->getRepository(Club::class)->findAll();

        $evenements= $evenementsRepository->findBy(['id_club' => $club]);
        // Find the Evenement entity by its ID
       // $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        // Get the club associated with the Evenement
       // $club = $evenement->getIdClub();

        // Find all Evenements associated with this Club
       // $evenements = $evenementsRepository->findBy(['id_club' => $club->getId()]);


        return $this->render('accueil/club/evenements.html.twig',['evenements' => $evenements,'club' => $club, ]);
    }






}
