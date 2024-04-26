<?php

namespace App\Controller;

use App\Entity\DossierStage;
use App\Entity\Offrestage;
use App\Form\DossierStageType;
use App\Repository\DossierStageRepository;
use App\Repository\EntretienRepository;
use App\Repository\OffrestageRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Request;

use App\Service\RevAiService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil_user')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    #[Route('/internships', name: 'app_internships')]
    public function internships(OffrestageRepository $offrestageRepository,DossierStageRepository $dossierStageRepository, PaginatorInterface $paginatorInterface, Request $req): Response
    {
        $dossier=$dossierStageRepository->findDossiersByUserIdWithOffreStage(55);
        $dossier = $paginatorInterface->paginate(
            $dossier, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
        $offrestage=  $offrestageRepository->findAll();
        $offrestage = $paginatorInterface->paginate(
            $offrestage, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
        return $this->render('accueil/internships.html.twig', [
            'offreStage' => $offrestage,
            'dossier'=>$dossier,
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
    public function apply($id, OffrestageRepository $offrestageRepository, ManagerRegistry $manager, Request $req, UtilisateurRepository $utilisateurRepository): Response
    {
        $em = $manager->getManager();
        $dossier = new DossierStage();
        $dossier->setIdUser($utilisateurRepository->find(60));
        $dossier->setIdOffre($offrestageRepository->find($id));
        $form = $this->createForm(DossierStageType::class, $dossier);
    
        $form->handleRequest($req);
    
        if ($form->isSubmitted() ) {
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
            $dossier->setCopieCin( $copieCinFileName);
            // Persist the DossierStage object
            $em->persist($dossier);
            $em->flush();
    
            // Redirect to the homepage or any other route
            return $this->redirectToRoute('app_accueil_user');
        }
    
        return $this->render("accueil/apply.html.twig", [
            'offreStage' => $offrestageRepository->find($id),
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/test', name: 'testEntity')]
    public function testEntity(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render("accueil/test.html.twig", ['list' => $utilisateurRepository->findAll()]);
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
    public function upcomingInterviews(EntretienRepository $entretienRepository,PaginatorInterface $paginatorInterface, Request $req ): Response
    {
        $currentDate = new \DateTime();
        // wa9t twali fama session bch nbdl l 55 bl IDUSER ELI CONNECTE
        $interviews=$entretienRepository->findInterviewsByIdUserAndDateGreaterThan(55,$currentDate);
        $interviews = $paginatorInterface->paginate(
            $interviews, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
        return $this->render('accueil/upcoming.html.twig', [
            'controller_name' => 'AccueilController',
            'interviews'=>$interviews,
        ]);
    }
    #[Route('/myinternships', name: 'app_myinternships')]
    public function myinternships(DossierStageRepository $dossierStageRepository , PaginatorInterface $paginatorInterface, Request $req, OffrestageRepository $offrestageRepository): Response
    {
        $offrestage=  $offrestageRepository->findAll();
        $offrestage = $paginatorInterface->paginate(
            $offrestage, /* query NOT result */
            $req->query->getInt('page', 1),
            1
        );
            $dossier=$dossierStageRepository->findDossiersByUserIdWithOffreStage(55);
            $dossier = $paginatorInterface->paginate(
                $dossier, /* query NOT result */
                $req->query->getInt('page', 1),
                1
            );
        return $this->render('accueil/myinternships.html.twig', [
            'controller_name' => 'AccueilController',
            'dossier'=>$dossier,
            'offreStage' => $offrestage,
        ]);
    }
}
