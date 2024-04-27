<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRecRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(Request $request, EntityManagerInterface $entityManager,UtilisateurRepository $UtilisateurRepository,): Response
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

    #[Route('/accueil/notifications', name: 'app_notifs')]
    public function notification(Request $request, EntityManagerInterface $entityManager,UtilisateurRepository $UtilisateurRepository,ReclamationRepository $reclamationRepository,ReponseRecRepository $ReponseRecRepository): Response
    {

        $reclamationstraité = $reclamationRepository->findReclamationsByUserAndEtat(56);
        $reclamationsnontraité= $reclamationRepository->findReclamationsByUserAndEtatNonTraite(56);
        foreach ($reclamationstraité as $rec)
         {
            $reponse=$ReponseRecRepository->findReponseById_Rec($rec->getId());
            $message='Status : Treated
            Response :
            '. $reponse->getDescription();
            pnotify()->addSuccess($message,$rec->getTitre());
        }
        foreach ($reclamationsnontraité as $rec)
         {
            $title= $rec->getTitre();
            $message = ' Status : Pending ... 
            We will reply as soon as possible ' ;
            pnotify()->addWarning($message,$title);
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
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
            
        }
        return $this->renderForm('base.html.twig', [
            'controller_name' => 'AccueilController',
            'form' => $form,
        ]);
    }
}
