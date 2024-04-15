<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }


    #[Route('/{id_conv}/add', name: 'app_message_show_by_id_conv')]
    public function showByIdConv($id_conv,EntityManagerInterface $entitymanager , UtilisateurRepository $utilisateurRepository,MessageRepository $messageRepository, ConversationRepository $conversationRepository,Request $request): Response
    {
        $message = new Message();
        $currentDate = new \DateTime();
        $user = $utilisateurRepository->findOneBy(['id' => 56]);
        $conversation = $conversationRepository->find($id_conv);
        
        $message->setDate($currentDate);
         $message->setIdUser($user);
         $message->setIdConv($conversation);

         $form = $this->createForm(MessageType::class, $message);
         $form->handleRequest($request);
        $messages = $messageRepository->findBy(['id_conv' => $id_conv]);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entitymanager->persist($message);
            $entitymanager->flush();

            return $this->redirectToRoute('app_message_show_by_id_conv', ['id_conv' => $id_conv], Response::HTTP_SEE_OTHER);
        }
        return $this->render('message/show.html.twig', [
            'conversation' => $conversation,
            'messages' => $messages,
            '_fragment' => 'footer',
            'form' => $form->createView(),
        ]);
    }





    #[Route('/{id_conv}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_message_show_by_id_conv', [], Response::HTTP_SEE_OTHER);
    }
}
