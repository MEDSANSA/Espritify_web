<?php

namespace App\Controller\Admin;

use App\Entity\DossierStage;
use App\Entity\Entretien;
use App\Entity\Message;
use App\Entity\Offrestage;
use App\Entity\ReponseRec;
use App\Form\AddDossierStageType;
use App\Form\DossierStageType;
use App\Form\EntretienType;
use App\Form\OffreStageType;
use App\Form\ReponseRecType;
use App\Repository\ConversationRepository;
use App\Repository\DossierStageRepository;
use App\Repository\EntretienRepository;
use App\Repository\MessageRepository;
use App\Repository\OffreStageRepository;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRecRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use MathPHP\Statistics\Average;
class accueilController extends AbstractController
{
    #[Route('/admin/accueil', name: 'app_admin_accueil')]
    public function index(OffrestageRepository $offreRepository,  DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
    {
        $results = $offreRepository->countApplicationsPerOffer();
        $nmbrOfApplications= $dossierStageRepository->countRows();
        $entretiens = $entretienRepository->findAll();

        // Initialize an array to store the count of entretiens for each day
        $entretiensPerDay = [];
    
        // Loop through each entretien and count the number of entretiens for each day
        foreach ($entretiens as $entretien) {
            $date = $entretien->getDate()->format('Y-m-d'); // Format the date to 'Y-m-d' format
            if (!isset($entretiensPerDay[$date])) {
                $entretiensPerDay[$date] = 0; // Initialize count to 0 for new day
            }
            $entretiensPerDay[$date]++; // Increment the count for the day
        }
    
        // Calculate the average number of entretiens per day
        $averageEntretiensPerDay = Average::mean(array_values($entretiensPerDay));
        return $this->render('admin/accueil/index.html.twig', [
            'results' => $results,
            'nmbrApplications'=>$nmbrOfApplications,
            'controller_name' => 'accueilController',
            'entretiensPerDay' => $entretiensPerDay,
            'averageEntretiensPerDay' => $averageEntretiensPerDay,
        ]);
    }

    #[Route('/admin/offrestage', name: 'app_admin_offrestage')]
    public function offrestage(EntityManagerInterface $entityManager, Request $request, OffrestageRepository $offrestageRepository, DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
    {    $results = $offrestageRepository->countApplicationsPerOffer();
        $search = $request->query->get('q');
        if (!$search) {
            $offre = $offrestageRepository->findAll();
        } else {
            $offre = $entityManager
                ->getRepository(Offrestage::class)
                ->createQueryBuilder('q')
                ->where('q.titre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $search . '%')
                ->getQuery()
                ->getResult();
        }
       
        $offrestage = new Offrestage();
        $form = $this->createForm(OffreStageType::class, $offrestage);
        return $this->render('admin/accueil/offrestage.html.twig', [
            'controller_name' => 'accueilController',
            'offreStage' => $offre,
            'dossierStage' => $dossierStageRepository->findAll(),
            'entretien' => $entretienRepository->findAll(),
            'form' => $form->createView(),
            'search' => $search,
            'results' => $results,
        ]);
    }

    
    #[Route('/edit/{id}', name: 'app_admin_edit')]
    public function editOffre($id,ManagerRegistry $manager, Request $req,  OffrestageRepository $offrestageRepository, DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
    {  
       
        $em= $manager->getManager(); //Doctrine manager
        $offrestage = $offrestageRepository->find($id); 
        $form= $this->createForm(OffreStageType::class,$offrestage );
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()){
            $em->persist($offrestage);//Enregistrement 
            $em->flush(); // pour executer
            return $this->redirectToRoute("app_admin_offrestage");
           
        }
        return $this->renderForm("admin/accueil/editOffre.html.twig", ["form"=>$form, 'offreStage' => $offrestage,
        'dossierStage' => $dossierStageRepository->findAll(),
        'entretien' => $entretienRepository->findAll(),
        
    ]);
    }
    #[Route('/admin/deleteOffre/{id}', name: 'app_admin_delete_offre')]
    public function deleteOffre(ManagerRegistry $manager, Request $req,$id,OffrestageRepository $offrestageRepository, DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
    {  
        $em= $manager->getManager();
        $offrestage=$offrestageRepository->find($id);
        $form= $this->createForm(OffreStageType::class,$offrestage );
        $em->remove($offrestage);
        $em->flush(); // pour executer
        return $this->renderForm("admin/accueil/offrestage.html.twig", ['offreStage' => $offrestageRepository->findAll(),
        'dossierStage' => $dossierStageRepository->findAll(),
        'entretien' => $entretienRepository->findAll(),
        'form'=> $form]);
    }

    #[Route('/admin/addOffre', name: 'app_admin_add_offre')]
    public function AddOffre(ManagerRegistry $manager, Request $req, OffrestageRepository $offrestageRepository, DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
    {
        
        $em= $manager->getManager(); //Doctrine manager
        $offrestage = new Offrestage(); 
        $form= $this->createForm(OffreStageType::class,$offrestage );
       
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()){
            $em->persist($offrestage);//Enregistrement 
            $em->flush(); // pour executer
            return $this->redirectToRoute("app_admin_offrestage");
        }
        return $this->render("admin/accueil/offrestage.html.twig", ["form"=>$form->createView(), 'offreStage' => $offrestageRepository->findAll(),
        'dossierStage' => $dossierStageRepository->findAll(),
        'entretien' => $entretienRepository->findAll(),]);
    }
    #[Route('/admin/dossierstage', name: 'app_admin_dossier_stage')]
    public function dossierstage(DossierStageRepository $dossierStageRepository, OffrestageRepository $offrestageRepository): Response
    {
        $dossier=$dossierStageRepository->findAll();
       
        $dossier= new DossierStage();
        $form= $this->createForm(AddDossierStageType::class,$dossier );
        return $this->render('admin/accueil/dossierstage.html.twig', [
            'form' => $form->createView(),
            'dossierStage' => $dossier,
            'offreStage'=>$offrestageRepository->findAll(),

        ]);
    }
    #[Route('/admin/editDossier/{idUser}/{idOffre}', name: 'app_admin_edit_dossier')]
    public function editDossier($idUser, $idOffre, ManagerRegistry $manager, Request $req, DossierStageRepository $dossierStageRepository, OffrestageRepository $offrestageRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $em= $manager->getManager();
        $dossier = $dossierStageRepository->findByUserIdAndOffreId($idUser,$idOffre); 
        $form= $this->createForm(AddDossierStageType::class,$dossier );
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()){
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
    
            return $this->redirectToRoute("app_admin_dossier_stage");
           
        }
    return $this->renderForm("admin/accueil/editDossier.html.twig", [ 
        'form' => $form
    ]);

    }
    #[Route('/admin/test', name: 'app_admin_accueiel')]
    public function test( DossierStageRepository $dossierStageRepository): Response
    {
        
        return $this->render('admin/accueil/test.html.twig', [
            'controller_name' => 'accueilController',
            'dossierStage' => $dossierStageRepository->findAll(),

        ]);
    }


    #[Route('/addDossierstage', name: 'app_admin_add_dossier')]
    public function apply(  ManagerRegistry $manager, Request $req, UtilisateurRepository $utilisateurRepository, DossierStageRepository $dossierStageRepository): Response

    {
        
        $em= $manager->getManager();
        $dossier = new DossierStage(); 
        $form= $this->createForm(AddDossierStageType::class,$dossier );
        
        $form->handleRequest($req);
        dump($req->request->all());
        if ($form->isSubmitted()&& $form->isValid()){
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
    
            
            return $this->redirectToRoute('app_admin_dossier_stage');
        }
        return $this->render("admin/accueil/dossierstage.html.twig", [     'form' => $form->createView(), 'dossierStage'=> $dossierStageRepository->findAll()]);
    }
    #[Route('/admin/deletedossier/{idUser}/{idOffre}', name: 'app_admin_delete_dossier')]
    public function deleteDossier(ManagerRegistry $manager, $idUser,$idOffre, DossierStageRepository $dossierStageRepository): Response
    {  
        $em= $manager->getManager();
        $dossier = $dossierStageRepository->findByUserIdAndOffreId($idUser,$idOffre);
        $form= $this->createForm(AddDossierStageType::class,$dossier );
        $em->remove($dossier);
        $em->flush(); // pour executer
        return $this->renderForm("admin/accueil/dossierstage.html.twig", [
        'dossierStage' => $dossierStageRepository->findAll(),
     
        'form'=> $form]);
    }
    #[Route('/admin/entretien', name: 'app_admin_entretien')]
    public function entretien(EntretienRepository $entretienRepository, Request $request): Response
    {
        // Récupérer les entretiens depuis le repository
        $entretiens = $entretienRepository->findAll();

        // Trier les entretiens par description
        usort($entretiens, function ($a, $b) {
            return $a->getDescription() <=> $b->getDescription();
        });

        // Créer un nouvel objet Entretien pour le formulaire
        $entretien = new Entretien();
        $form = $this->createForm(EntretienType::class, $entretien);

        return $this->render('admin/accueil/interview.html.twig', [
            'controller_name' => 'accueilController',
            'entretien' => $entretiens, // Passer les entretiens triés à la vue
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin/editInterview/{idUser}/{idOffre}', name: 'app_admin_edit_interview')]
    public function edit_interview($idUser,$idOffre,ManagerRegistry $manager, Request $req, EntretienRepository $entretienRepository): Response
    {  
       
        $em= $manager->getManager(); //Doctrine manager
        $entretien = $entretienRepository->findByUserIdAndOffreId($idUser,$idOffre); 
        $form= $this->createForm(EntretienType::class,$entretien );
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()){
            $em->persist($entretien);//Enregistrement 
            $em->flush(); // pour executer
            return $this->redirectToRoute("app_admin_entretien");
           
        }
        return $this->renderForm("admin/accueil/editInterview.html.twig", ["form"=>$form, 
        
        'entretien' => $entretien,
    ]);
    }
   
    #[Route('/admin/addEntretien', name: 'app_admin_add_entretien')]
    public function AddEntretien(ManagerRegistry $manager, Request $req, EntretienRepository $entretienRepository): Response
    {
        
        $em= $manager->getManager(); //Doctrine manager
        $entretien = new Entretien(); 
        $form= $this->createForm(EntretienType::class,$entretien );
       
        $form->handleRequest($req);
        if ($form->isSubmitted()&& $form->isValid()){
            $em->persist($entretien);//Enregistrement 
            $em->flush(); // pour executer
            return $this->redirectToRoute("app_admin_entretien");
        }
        return $this->render("admin/accueil/interview.html.twig", ["form"=>$form->createView(), 
        'entretien' => $entretienRepository->findAll(),]);
    }
    #[Route('/admin/deleteEntretien/{idUser}/{idOffre}', name: 'app_admin_delete_entretien')]
    public function deleteEntretien(ManagerRegistry $manager, Request $req,$idUser,$idOffre,OffrestageRepository $offrestageRepository, DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
    {  
        $em= $manager->getManager();
        $entretien=$entretienRepository->findByUserIdAndOffreId($idUser,$idOffre);
        $form= $this->createForm(EntretienType::class,$entretien );
        $em->remove($entretien);
        $em->flush(); // pour executer
        return $this->renderForm("admin/accueil/interview.html.twig", [
       
        'entretien' => $entretienRepository->findAll(),
        'form'=> $form]);
    }



    #[Route('/admin/testttt', name: 'app_admin_dossier_stageee')]
    public function testtt(DossierStageRepository $dossierStageRepository): Response
    {
        $dossier= $dossierStageRepository->findAll();
        
        

        // Pass the full image path to the Twig template
        
        return $this->render('admin/accueil/testtt.html.twig', [
            'dossierStage' => $dossierStageRepository->findAll(),
          
        ]);
    }
//filtre
    #[Route('/admin/getDossierByOffreId/{offreId}', name: 'app_admin_dossier_stageee')]
    public function getDossierByOffreId($offreId, DossierStageRepository $dossierStageRepository): JsonResponse
{
    $dossiers = $dossierStageRepository->findBy(['id_offre' => $offreId]);
    $data = [];
    foreach ($dossiers as $dossier) {
        // Assuming you have some properties you want to include in the response
        $data[] = [
            'cv' => $dossier->getCv(),
            'convention' => $dossier->getConvention(),
            'cin'=>$dossier->getCopieCin(),
            'titre'=>$dossier->getIdOffre()->getTitre(),
            'descriptionSoc'=>$dossier->getIdOffre()->getDescSoc(),
            'nom' => $dossier->getIdUser()->getNom(),
            'prenom' => $dossier->getIdUser()->getPrenom(),
            'email'=>$dossier->getIdUser()->getEmail(),
            'idUser'=>$dossier->getIdUser(),
            // Add more properties as needed
        ];
    }

    return new JsonResponse($data);
}


#[Route('/admin/sort', name: 'app_admin_sort')]
public function sort( EntityManagerInterface $entityManager, Request $request, OffrestageRepository $offrestageRepository, DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
{
    $offre = $offrestageRepository->findAll();
    
    $search = $request->query->get('q');
        if (!$search) {
            usort($offre, function ($a, $b) {
                return $a->getTitre() <=> $b->getTitre();
            });
        } else {
            $offre = $entityManager
                ->getRepository(Offrestage::class)
                ->createQueryBuilder('q')
                ->where('q.titre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $search . '%')
                ->getQuery()
                ->getResult();
        }
       
        $offrestage = new Offrestage();
        $form = $this->createForm(OffreStageType::class, $offrestage);
        return $this->render('admin/accueil/offrestage.html.twig', [
            'controller_name' => 'accueilController',
            'offreStage' => $offre,
            'dossierStage' => $dossierStageRepository->findAll(),
            'entretien' => $entretienRepository->findAll(),
            'form' => $form->createView(),
            'search' => $search,
        ]);
   
    
}
    #[Route('/admin/stat', name: 'app_admin_stat')]
    public function average(OffrestageRepository $offreRepository, DossierStageRepository $dossierStageRepository, EntretienRepository $entretienRepository): Response
    {
        $results = $offreRepository->countApplicationsPerOffer();
        $nmbrOfApplications= $dossierStageRepository->countRows();
        
        $entretiens = $entretienRepository->findAll();

        // Initialize an array to store the count of entretiens for each day
        $entretiensPerDay = [];
    
        // Loop through each entretien and count the number of entretiens for each day
        foreach ($entretiens as $entretien) {
            $date = $entretien->getDate()->format('Y-m-d'); // Format the date to 'Y-m-d' format
            if (!isset($entretiensPerDay[$date])) {
                $entretiensPerDay[$date] = 0; // Initialize count to 0 for new day
            }
            $entretiensPerDay[$date]++; // Increment the count for the day
        }
    
        // Calculate the average number of entretiens per day
        $averageEntretiensPerDay = Average::mean(array_values($entretiensPerDay));
    
        // Return the data to the Twig template
        return $this->render('admin/accueil/teststat.html.twig', [
            'entretiensPerDay' => $entretiensPerDay,
            'averageEntretiensPerDay' => $averageEntretiensPerDay,
        ]);
    }

    #[Route('/admin/accueil/reclamation', name: 'app_admin_reclamation')]
    public function ShowReclamation(Request $request, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
 $searchTerm = $request->query->get('q');
    $showNonTraite = $request->query->get('show_non_traite', false);
    $showTraite = $request->query->get('show_traite', false);

    $queryBuilder = $reclamationRepository->createQueryBuilder('r');

    if ($searchTerm) {
        $queryBuilder
            ->where('r.titre LIKE :searchTerm')
            ->orWhere('r.description LIKE :searchTerm')
            ->orWhere('r.etat LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
    }

    if ($showNonTraite && !$showTraite) {
        $queryBuilder->andWhere('r.etat = :etat')
            ->setParameter('etat', 'non traité');
    } elseif (!$showNonTraite && $showTraite) {
        $queryBuilder->andWhere('r.etat = :etat')
            ->setParameter('etat', 'traité');
    } 
    $reclamations = $queryBuilder->getQuery()->getResult();

    $reponseRec = new ReponseRec();
    $reponseRec->setDate(new \DateTime());
    $form = $this->createForm(ReponseRecType::class, $reponseRec);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reclamationId = $request->request->get('reclamation_id');
        $reclamation = $reclamationRepository->find($reclamationId);

        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        $reclamation->setEtat('traité');
        $reponseRec->setIdRec($reclamation);

        $entityManager->persist($reclamation);
        $entityManager->persist($reponseRec);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_reclamation');
    }

    return $this->renderForm('/admin/accueil/Reclamation_Admin.html.twig', [
        'Reclamation' => $reclamations,
        'reponse_rec' => $reponseRec,
        'form' => $form,
        'searchTerm' => $searchTerm,
        'showNonTraite' => $showNonTraite,
        'showTraite' => $showTraite,
    ]);
    }
    
    #[Route('/admin/accueil/conversation', name: 'app_admin_conversation')]
    public function ShowrConversation(ConversationRepository $ConversationRepository,Request $request, EntityManagerInterface $entityManager): Response
    {   
        $searchTerm = $request->query->get('q');
        $sortByMostLikes = $request->query->get('sort_likes'); // Check if sorting by likes is requested
    
        // Start building the query
        $queryBuilder = $ConversationRepository->createQueryBuilder('c');
    
        if ($searchTerm) {
            $queryBuilder->where('c.titre LIKE :searchTerm OR c.description LIKE :searchTerm')
                         ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }
    
        if ($sortByMostLikes) {
            $queryBuilder->orderBy('c.likes', 'DESC'); // Sort by most likes if requested
        }
    
        $conversations = $queryBuilder->getQuery()->getResult();
    
        return $this->render('/admin/accueil/Conversation_Admin.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    #[Route('/admin/accueil/Comments', name: 'app_admin_message')]
    public function ShowrMessage(EntityManagerInterface $entityManager,Request $request): Response
    {
        $searchTerm = $request->query->get('q');
        if (!$searchTerm) {
            $recs = $entityManager
                ->getRepository(Message::class)
                ->findAll();
        } else {
            $recs = $entityManager
                ->getRepository(Message::class)
                ->createQueryBuilder('q')
                ->Where('q.description LIKE :searchTerm ')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }
        return $this->render('/admin/accueil/Message_Admin.html.twig', [
            
            'Message' =>$recs,
        ]);
    }

    #[Route('/admin/accueil/reponse_reclamation', name: 'app_admin_reponse_rec')]
    public function ShowrReponseRec(EntityManagerInterface $entityManager,Request $request): Response
    {

        $searchTerm = $request->query->get('q');
        if (!$searchTerm) {
            $recs = $entityManager
                ->getRepository(ReponseRec::class)
                ->findAll();
        } else {
            $recs = $entityManager
                ->getRepository(ReponseRec::class)
                ->createQueryBuilder('q')
                ->Where('q.description LIKE :searchTerm ')
                ->orWhere('q.titre LIKE :searchTerm ')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }
        return $this->render('/admin/accueil/Reponse_Rec_Admin.html.twig', [
            
            'Reponse' => $recs,
        ]);
    }
   
    
    #[Route('/admin/reclamation/delete/{id}', name: 'app_admin_reclamation_delete')]
public function deleteReclamation(int $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
{
    $reclamation = $reclamationRepository->find($id);
        $entityManager->remove($reclamation);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_reclamation');
}

 
#[Route('/admin/conversation/delete/{id}', name: 'app_admin_conversation_delete')]
public function deleteConv(int $id, ConversationRepository $conversationRepository, EntityManagerInterface $entityManager): Response
{
    $conversation = $conversationRepository->find($id);
        $entityManager->remove($conversation);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_conversation');
}


#[Route('/admin/message/delete/{id}', name: 'app_admin_message_delete')]
public function deleteComment(int $id, MessageRepository $MessageRepository, EntityManagerInterface $entityManager): Response
{
        $MEssage = $MessageRepository->findOneBy(['id'=>$id]);
        $entityManager->remove($MEssage);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_message');
}

#[Route('/admin/reponse/delete/{id}', name: 'app_admin_reponse_rec_delete')]
public function deletereponse(int $id, ReponseRecRepository $ReponseRecRepository, EntityManagerInterface $entityManager): Response
{
    $Message = $ReponseRecRepository->findOneBy(['id'=>$id]);
        $entityManager->remove($Message);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_reponse_rec');
}

#[Route('/admin/accueil/conversation/{id}/comments', name: 'app_admin_conversation_comments')]
public function showComments($id, MessageRepository $messageRepository): Response
{
    $Message = $messageRepository->findBy(['id_conv' => $id]);
    return $this->render('/admin/accueil/Message_Admin.html.twig', [
        'Message' => $Message,
    ]);
}


   /* #[Route('/admin/stat', name: 'app_admin_stat')]
    public function yourAction(OffrestageRepository $offreRepository): Response
    {   $results = $offreRepository->countApplicationsPerOffer();

        return $this->render('admin/accueil/teststat.html.twig', [
            'results' => $results,
        ]);
    }*/
}