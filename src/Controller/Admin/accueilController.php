<?php

namespace App\Controller\Admin;

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
    public function ShowReclamation(Request $request, ReclamationRepository $reclamationRepository, ReponseRecRepository $reponseRecRepository, EntityManagerInterface $entityManager): Response
    {
        $reponseRec = new ReponseRec();
        $currentDate = new \DateTime();
        $form = $this->createForm(ReponseRecType::class, $reponseRec);
        $reponseRec->setDate($currentDate);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $reclamationId = $request->request->get('reclamation_id');
            $reclamation = $reclamationRepository->find($reclamationId);
            $reclamation->setEtat('traité');
 
            if (!$reclamation) {
                throw $this->createNotFoundException('Reclamation not found');
            }

            $reponseRec->setIdRec($reclamation);
            $entityManager->persist($reclamation);
            $entityManager->persist($reponseRec);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reclamation');
        }

        return $this->renderForm('/admin/accueil/Reclamation_Admin.html.twig', [
            'Reclamation' => $reclamationRepository->findAll(),
            'reponse_rec' => $reponseRec,
            'form' => $form,
        ]);
    }
    






    #[Route('/admin/accueil/conversation', name: 'app_admin_conversation')]
    public function ShowrConversation(ConversationRepository $ConversationRepository): Response
    {
        return $this->render('/admin/accueil/Conversation_Admin.html.twig', [
            
            'Conversation' =>$ConversationRepository->findAll(),
        ]);
    }

    #[Route('/admin/accueil/Comments', name: 'app_admin_message')]
    public function ShowrMessage(MessageRepository $MessageRepository): Response
    {
        return $this->render('/admin/accueil/Message_Admin.html.twig', [
            
            'Message' =>$MessageRepository->findAll(),
        ]);
    }

    #[Route('/admin/accueil/reponse_reclamation', name: 'app_admin_reponse_rec')]
    public function ShowrReponseRec(ReponseRecRepository $ReponseRecRepository): Response
    {
        return $this->render('/admin/accueil/Reponse_Rec_Admin.html.twig', [
            
            'Reponse' =>$ReponseRecRepository->findAll(),
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
    $MEssage = $MessageRepository->find($id);
        $entityManager->remove($MEssage);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_message');
}

#[Route('/admin/reponse/delete/{id}', name: 'app_admin_reponse_rec_delete')]
public function deletereponse(int $id, ReponseRecRepository $ReponseRecRepository, EntityManagerInterface $entityManager): Response
{
    $Message = $ReponseRecRepository->find($id);
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
