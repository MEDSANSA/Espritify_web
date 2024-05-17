<?php

namespace App\Controller;

use App\Entity\DossierStage;
use App\Entity\Offrestage;
use App\Form\DossierStageType;
use App\Entity\Club;
use App\Entity\Evenement;
use App\Entity\ParticipationEvenement;
use App\Repository\DossierStageRepository;
use App\Repository\EntretienRepository;
use App\Repository\OffreStageRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClubRepository;
use App\Repository\EvenementRepository;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Request;

use App\Service\RevAiService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRecRepository;
use App\Entity\Utilisateur;
use App\Repository\ParticipationEvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\Security\Core\Security;


class AccueilController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function indexe(Security $security, Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $UtilisateurRepository): Response
    {
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }
        $currentDate = new \DateTime();
        $user = $UtilisateurRepository->findOneBy(['id' => 58]);
        $reclamation = new Reclamation();
        $reclamation->setEtat("non traité");
        $reclamation->setIdUser($user);
        $reclamation->setDate($currentDate);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
            pnotify()->addSuccess('Your complaint has been addded successfully !.');
            return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('basefront.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'user' => $u,
        ]);
    }
    #[Route('/acceuil', name: 'app_app')]
    public function acceuil(): Response
    {
        return $this->render('Front/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    #[Route('/internships', name: 'app_internships')]
    public function internships(OffrestageRepository $offrestageRepository, DossierStageRepository   $dossierStageRepository, PaginatorInterface $paginatorInterface, Request $req): Response
    {
        $dossier = $dossierStageRepository->findDossiersByUserIdWithOffreStage(55);
        $dossier = $paginatorInterface->paginate(
            $dossier, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
        $offrestage =  $offrestageRepository->findAll();
        $offrestage = $paginatorInterface->paginate(
            $offrestage, /* query NOT result */
            $req->query->getInt('page', 1),
            3
        );
        return $this->render('accueil/internships.html.twig', [
            'offreStage' => $offrestage,
            'dossier' => $dossier,
        ]);
    }
    #[Route('/internship-details/{id}', name: 'app_internship-details')]
    public function internship_details($id, OffrestageRepository $offrestageRepository): Response
    {

        return $this->render('accueil/internship-details.html.twig', [
            'offreStage' => $offrestageRepository->find($id),

        ]);
    }
    #[Route('/apply/{id}', name: 'app_apply')]
    public function apply($id, Security $security, OffrestageRepository $offrestageRepository, ManagerRegistry $manager, Request $req, UtilisateurRepository $utilisateurRepository): Response
    {
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }
        $em = $manager->getManager();
        $dossier = new DossierStage();
        $dossier->setIdUser($utilisateurRepository->find($u->getUserIdentifier()));
        $dossier->setIdOffre($offrestageRepository->find($id));
        $form = $this->createForm(DossierStageType::class, $dossier);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            // Handle file uploads
            $cvFile = $form['cv']->getData();
            $conventionFile = $form['convention']->getData();
            $copieCinFile = $form['copie_cin']->getData();

            // Define the uploads directory
            $uploadsDirectory = $this->getParameter('uploads_directory');
            // Upload CV file
            $cvFileName = md5(uniqid()) . '.' . $cvFile->guessExtension();
            $cvFile->move($uploadsDirectory, $cvFileName);
            $dossier->setCv($cvFileName);
            // Upload convention file
            $conventionFileName = md5(uniqid()) . '.' . $conventionFile->guessExtension();
            $conventionFile->move($uploadsDirectory, $conventionFileName);
            $dossier->setConvention($conventionFileName);
            // Upload copie cin file
            $copieCinFileName = md5(uniqid()) . '.' . $copieCinFile->guessExtension();
            $copieCinFile->move($uploadsDirectory, $copieCinFileName);
            $dossier->setCopieCin($copieCinFileName);
            // Persist the DossierStage object
            $em->persist($dossier);
            $em->flush();

            // Redirect to the homepage or any other route
            return $this->redirectToRoute('app_front');
        }

        return $this->render("accueil/apply.html.twig", [
            'offreStage' => $offrestageRepository->find($id),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/callendar', name: 'app_accueil')]
    public function callendar(): Response
    {
        return $this->render('accueil/upcoming-interview.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    #[Route('/speech-recognition', name: 'speech_recognition')]
    public function speechRecognition(Request $request, RevAiService $revAiService): JsonResponse
    {
        // Handle file upload
        $audioFile = $request->files->get('audio_file');

        if (!$audioFile instanceof UploadedFile) {
            // Return error response
            return new JsonResponse(['error' => 'No audio file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        // Process speech recognition
        try {
            $partialTranscription = $revAiService->transcribePartialAudio($audioFile);
            $finalTranscription = $revAiService->transcribeFinalAudio($audioFile);

            // Return response with partial and final transcriptions
            return new JsonResponse([
                'partial_transcription' => $partialTranscription,
                'final_transcription' => $finalTranscription,
            ]);
        } catch (\Exception $e) {
            // Return error response
            return new JsonResponse(['error' => 'Speech recognition failed: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/speechi', name: 'app_accueil_user')]
    public function speechi(): Response
    {
        return $this->render('accueil/ai.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    #[Route('/upcoming', name: 'app_upcoming')]
    public function upcomingInterviews(Security $security, EntretienRepository $entretienRepository, PaginatorInterface $paginatorInterface, Request $req): Response
    {
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }
        $currentDate = new \DateTime();
        // wa9t twali fama session bch nbdl l 55 bl IDUSER ELI CONNECTE
        $interviews = $entretienRepository->findInterviewsByIdUserAndDateGreaterThan($u->getUserIdentifier(), $currentDate);
        $interviews = $paginatorInterface->paginate(
            $interviews, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
        return $this->render('accueil/upcoming.html.twig', [
            'controller_name' => 'AccueilController',
            'interviews' => $interviews,
        ]);
    }
    #[Route('/myinternships', name: 'app_myinternships')]
    public function myinternships(Security $security, DossierStageRepository $dossierStageRepository, PaginatorInterface $paginatorInterface, Request $req, OffrestageRepository $offrestageRepository): Response
    {
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }
        $offrestage =  $offrestageRepository->findAll();
        $offrestage = $paginatorInterface->paginate(
            $offrestage, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
        $dossier = $dossierStageRepository->findDossiersByUserIdWithOffreStage($u->getUserIdentifier());
        $dossier = $paginatorInterface->paginate(
            $dossier, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
        return $this->render('accueil/myinternships.html.twig', [
            'controller_name' => 'AccueilController',
            'dossier' => $dossier,
            'offreStage' => $offrestage,
        ]);
    }

    #[Route('/accueil', name: 'app_accueil')]
    public function index(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $UtilisateurRepository,): Response
    {
        $currentDate = new \DateTime();
        $user = $UtilisateurRepository->findOneBy(['id' => 58]);
        $reclamation = new Reclamation();
        $reclamation->setEtat("non traité");
        $reclamation->setIdUser($user);
        $reclamation->setDate($currentDate);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
            pnotify()->addSuccess('Your complaint has been addded successfully !.');
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('base.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
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
    // aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa



    #[Route('/accueil/notifications', name: 'app_notifs')]
    public function notification(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $UtilisateurRepository, ReclamationRepository $reclamationRepository, ReponseRecRepository $ReponseRecRepository): Response
    {

        $reclamationstraité = $reclamationRepository->findReclamationsByUserAndEtat(56);
        $reclamationsnontraité = $reclamationRepository->findReclamationsByUserAndEtatNonTraite(56);
        foreach ($reclamationstraité as $rec) {
            $reponse = $ReponseRecRepository->findReponseById_Rec($rec->getId());
            $message = 'Status : Treated
            Response :
            ' . $reponse->getDescription();
            pnotify()->addSuccess($message, $rec->getTitre());
        }
        foreach ($reclamationsnontraité as $rec) {
            $title = $rec->getTitre();
            $message = ' Status : Pending ... 
            We will reply as soon as possible ';
            pnotify()->addWarning($message, $title);
        }
        $currentDate = new \DateTime();
        $user = $UtilisateurRepository->findOneBy(['id' => 56]);
        $reclamation = new Reclamation();
        $reclamation->setEtat("non traité");
        $reclamation->setIdUser($user);
        $reclamation->setDate($currentDate);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
            pnotify()->addSuccess('Your complaint has been addded successfully !.');
            return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('basefront.html.twig', [
            'controller_name' => 'AccueilController',
            'form' => $form,
        ]);
    }

    #[Route('/club-details', name: 'app_club_details')]
    public function club_details(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request, ClubRepository $clubRepository): Response
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

    #[Route('/evenements-details/{id}', name: 'app_evenements_details')]
    public function evenement_details(EvenementRepository $evenementsRepository, $id): Response
    {
        $club =  $this->getDoctrine()->getRepository(Club::class)->find($id);

        $evenements = $evenementsRepository->findBy(['id_club' => $club]);



        return $this->render('accueil/club/evenements.html.twig', ['evenements' => $evenements, 'club' => $club,]);
    }

    #[Route('/evenement/{id}/participer', name: 'participer_evenement', methods: ['POST'])]
    public function participer(Evenement $evenement, EntityManagerInterface $entityManager,Security $security): Response
    {
        $u = $security->getUser();
        $user = $entityManager->getRepository(Utilisateur::class)->find($u->getUserIdentifier());



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
    { {
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
    public function mesParticipations(Security $security): Response
    {

        $u = $security->getUser();
        $userId = $u->getUserIdentifier(); // L'ID de l'utilisateur
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
