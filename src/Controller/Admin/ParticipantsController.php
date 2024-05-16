<?php

namespace App\Controller\Admin;

use App\Entity\ParticipationEvenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class ParticipantsController extends AbstractController
{
    #[Route('/participations', name: 'participations', methods: ['GET'])]
    public function participations(): Response
    {
        // Récupérer toutes les participations avec les événements associés
        $participations = $this->getDoctrine()->getRepository(ParticipationEvenement::class)->findAll();

        // Passer les participations à la vue Twig pour l'affichage
        return  $this->render('Admin/participants/participations.html.twig', [
            'participations' => $participations,
        ]);
    }
}