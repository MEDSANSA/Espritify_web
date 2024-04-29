<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Evenement;
use App\Entity\ParticipationEvenement;
use App\Entity\Utilisateur;
use App\Repository\ParticipationEvenementRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\ClubRepository;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;


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
       public function club_details(EntityManagerInterface $entityManager, PaginatorInterface $paginator,Request $request, ClubRepository $clubRepository): Response
       {
           $queryBuilder = $entityManager->getRepository(Club::class)->createQueryBuilder('c');
           $queryBuilder->orderBy('c.intitule', 'ASC');

           // Paginer les résultats
           $pagination = $paginator->paginate(
               $queryBuilder->getQuery(),               // Requête à paginer
               $request->query->getInt('page', 1),     // Numéro de page par défaut
               3                                      // Nombre d'éléments par page
           );
           return $this->render('accueil/club/club.html.twig', [
               'controller_name' => 'AccueilController',
               'pagination' => $pagination,

           ]);
       }

    #[Route('/evenements-details/{id}' , name : 'app_evenements_details')]
    public function evenement_details(EvenementRepository $evenementsRepository, $id): Response
    {  $club =  $this->getDoctrine()->getRepository(Club::class)->find($id);

        $evenements= $evenementsRepository->findBy(['id_club' => $club]);



        return $this->render('accueil/club/evenements.html.twig',['evenements' => $evenements,'club' => $club, ]);
    }

    #[Route('/evenement/{id}/participer', name: 'participer_evenement', methods: ['POST'])]
    public function participer(Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        // $user = $this->getUser();
        $user = $entityManager->getRepository(Utilisateur::class)->find(1);



        // Vérifier si l'utilisateur et l'événement existent
        if (!$user || !$evenement) {
            // Rediriger avec un message d'erreur
            $this->addFlash('error', 'Erreur: Impossible de trouver l\'utilisateur ou l\'événement.');
            return $this->redirectToRoute('app_club_details');
        }

        // Vérifier si l'utilisateur participe déjà à cet événement
        $participationExistante = $this->getDoctrine()->getRepository(ParticipationEvenement::class)->findOneBy([
            'id_user' => $user,
            'id_evenement' => $evenement
        ]);

        // Si la participation existe déjà, afficher un message d'erreur
        if ($participationExistante) {
            $this->addFlash('error', 'Vous participez déjà à cet événement.');
            return $this->redirectToRoute('app_club_details');
        }
        //créer une nouvelle entité de participation
        $participation = new ParticipationEvenement();


        //Enregistrer la nouvelle participation dans la base de données
        $participation->setIdUser($user);
        $participation->setIdEvenement($evenement);

        // Ajoutez cette participation à l'utilisateur
        $user->addParticipationEvenement($participation);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($participation);
        $entityManager->flush();

        $this->addFlash('success', 'Votre participation a été enregistrée avec succès !');

        return $this->redirectToRoute('app_club_details', [], Response::HTTP_SEE_OTHER);


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


    #[Route('/evenement/mesparticipations', name: 'mes_participations', methods: ['GET'])]
    public function mesParticipations(): Response
    {

        $userId = 1; // L'ID de l'utilisateur
        $participations = $this->getDoctrine()->getRepository(ParticipationEvenement::class)->findBy(['id_user' => $userId]);
        // Initialisez un tableau pour stocker les événements associés
        $evenements = [];

        // Bouclez sur chaque participation pour récupérer les événements associés
        foreach ($participations as $participation) {
            // Récupérez l'événement associé à la participation
            $evenement = $participation->getIdEvenement();
            // Ajoutez l'événement au tableau des événements
            $evenements[] = $evenement;
        }
        // Vérifiez si une participation a été trouvée
        if (!$participations) {
            throw $this->createNotFoundException('Participation non trouvée');
        }

        // Passer la participation à votre vue Twig pour l'affichage
        return $this->render('accueil/club/mesparticipations.html.twig', [
            'evenements' => $evenements,
        ]);
    }




}
