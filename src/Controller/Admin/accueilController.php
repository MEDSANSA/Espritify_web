<?php

namespace App\Controller\Admin;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Reclamation;
use App\Entity\ReponseRec;
use App\Form\ReponseRecType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRecRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class accueilController extends AbstractController
{

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
    public function ShowrMessage(MessageRepository $MessageRepository,EntityManagerInterface $entityManager,Request $request): Response
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





}
