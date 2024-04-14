<?php

namespace App\Controller;

use App\Entity\DossierStage;
use App\Entity\Offrestage;
use App\Form\DossierStageType;
use App\Repository\OffrestageRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    #[Route('/internships', name: 'app_internships')]
    public function internships(OffrestageRepository $offrestageRepository): Response
    {
        return $this->render('accueil/internships.html.twig', [
            'offreStage' => $offrestageRepository->findAll(),
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
        $dossier->setIdUser($utilisateurRepository->find(55));
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
            return $this->redirectToRoute('app_accueil');
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
    

}
