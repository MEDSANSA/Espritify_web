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
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Dompdf\Options;



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

    #[Route('/calendar', name: 'calendar')]
    public function calendar(): Response
    {
        return $this->render('accueil/club/Calendar.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /**
     * @Route("/api/events", name="api_events_index", methods={"GET"})
     */
    public function indexCalendar(): JsonResponse
    {
        {
            // Récupérez vos événements depuis la base de données (ex: à l'aide d'une entité Doctrine)
            $events = $this->getDoctrine()->getRepository(Evenement::class)->findAll();

            // Formattez les événements pour les renvoyer au format JSON
            $formattedEvents = [];
            foreach ($events as $event) {
                $formattedEvents[] = [
                    'title' => $event->getTitre(),
                    'start' => $event->getDateDebut()->format('Y-m-d'),
                    'end' => $event->getDateFin()->format('Y-m-d'),

                    // Ajoutez d'autres propriétés si nécessaire
                ];
            }

            return new JsonResponse($formattedEvents);
        }
    }

    /**
     * @Route("/api/events/new", name="api_events_new", methods={"POST"})
     */
    public function createEvent(Request $request): JsonResponse
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier si les données sont valides
        if (!isset($data['title']) || !isset($data['start'])) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        // Créer un nouvel événement
        $event = new Evenement();
        $event->setTitre($data['title']);
        $event->setDateDebut(new \DateTime($data['start']));

        // Enregistrer l'événement dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        // Répondre avec l'ID de l'événement créé
        return new JsonResponse(['id' => $event->getId()], Response::HTTP_CREATED);
    }


    #[Route('/catalogue', name: 'catalogue')]
    public function generatePdfForWeek(EvenementRepository $evenementRepository): Response
    {
        // Calculer la date de début de la semaine (par exemple, lundi de cette semaine)
        $startDate = new \DateTime('monday this week');

        // Récupérer les événements pour la semaine
        $weekEvents = $evenementRepository->findEventsForWeek($startDate);

        // Récupérer les détails des clubs pour les événements de la semaine
        $clubDetails = [];
        foreach ($weekEvents as $event) {
            $clubId = $event->getIdClub()->getId();
            if (!isset($clubDetails[$clubId])) {
                $clubDetails[$clubId] = $this->getDoctrine()->getRepository(Club::class)->find($clubId);
            }
        }

        // Rendre le template HTML du PDF avec les données nécessaires
        $html = $this->renderView('accueil/club/weekly_catalogue.html.twig', [
            'events' => $weekEvents,
            'clubDetails' => $clubDetails,
        ]);
        // Instancier Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Générer une réponse avec le contenu PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');

        // Vous pouvez également définir le nom du fichier PDF s'il doit être téléchargé
        // $response->headers->set('Content-Disposition', 'attachment; filename="weekly_events_catalog.pdf"');

        return $response;
    }
}
