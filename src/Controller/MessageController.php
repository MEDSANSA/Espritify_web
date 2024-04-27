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
use Twilio\Rest\Client;


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
            $this->addFlash('success', 'Comment Added successfully');
   
        $account_sid = $_ENV['TWILIO_ACCOUNT_SID'];
        $auth_token = $_ENV['TWILIO_AUTH_TOKEN'];
        $twilio_number = $_ENV['TWILIO_PHONE_NUMBER'];
        $client = new Client($account_sid, $auth_token);

    
        $recipient_phone_number = '+21695103375';  

        $client->messages->create(
            $recipient_phone_number,
            [
                'from' => $twilio_number,
                'body' => 'A new comment has been added to your conversation! ,
                 login and check it',
            ]
        );


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

    

    #[Route('/delete/{id}', name: 'app_message_delete')]
    public function delete($id,Request $request, Message $message, EntityManagerInterface $entityManager,MessageRepository $messageRepository): Response
    {
        $message = $messageRepository->find($id);

    $conversation = $message->getIdConv(); // This is the Conversation entity
    $id_conv = $conversation ? $conversation->getId() : null; // Get the actual ID as an integer

    
        $entityManager->remove($message);
        $entityManager->flush();

        $this->addFlash('success', 'Comment deleted successfully');
    
    return $this->redirectToRoute('app_message_show_by_id_conv', ['id_conv' => $id_conv], Response::HTTP_SEE_OTHER);
}
}