<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Form\ConversationType;
use App\Repository\ConversationRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ConsoleTVs\Profanity\Builder;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Security\Core\Security;
#[Route('/conversation')]
class ConversationController extends AbstractController
{

    private Builder $profanity;
    public function __construct(Builder $profanity)
    {
        $this->profanity = $profanity;
    }

   
    #[Route('/', name: 'app_conversation_index', methods: ['GET', 'POST'])]
    public function index( Security $security,ConversationRepository $conversationRepository, UtilisateurRepository $utilisateurRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }
        $currentDate = new \DateTime();
        $conversation = new Conversation();
        $user = $utilisateurRepository->findOneBy(['id' => 56]);
        $conversation->setIdUser($user);
        $conversation->setDate($currentDate);
        $editConversation = new Conversation();
        $editForm = $this->createForm(ConversationType::class, $editConversation, [
            'attr' => ['name' => 'edit_conversation_form']
        ]);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $titre1 = $editForm->get('titre')->getData();
            $description1 = $editForm->get('description')->getData();
    
            if (
                $this->profanity->blocker($titre1)->filter() !== $titre1 ||
                $this->profanity->blocker($description1)->filter() !== $description1
            ) {
                $this->addFlash('error', 'The title or description contains profane language. Please remove it.');
                return $this->renderForm('conversation/index.html.twig', [
                    'conversations' => $conversationRepository->findAll(),
                    'form1' => $editForm,
                    'user'=>$u,
                ]);
            }
    
            $convID = $request->request->get('reclamation_id');
            $conv = $conversationRepository->find($convID);
            $conv->setTitre($titre1);
            $conv->setDescription($description1);
            $conv->setDate($currentDate);
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_conversation_index');
        }

        return $this->renderForm('conversation/index.html.twig', [
            'conversations' => $conversationRepository->findAll(),
            'form1' => $editForm,
            'user'=>$u,
        ]);
    }


    #[Route('/new', name: 'app_conversation_new', methods: ['GET', 'POST'])]
    public function new(Security $security,Request $request, EntityManagerInterface $entityManager, ConversationRepository $conversationRepository,UtilisateurRepository $utilisateurRepository): Response
    {   
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }
        $currentDate = new \DateTime();
        $conversation = new Conversation();
        $conversation->setDate($currentDate);
        $user = $utilisateurRepository->findOneBy(['id' => 56]);
        $conversation->setIdUser($user);
        $createForm = $this->createForm(ConversationType::class, $conversation, [
            'attr' => ['name' => 'create_conversation_form']
        ]);
        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $titre = $createForm->get('titre')->getData();
            $description = $createForm->get('description')->getData();
    
            // Check for profanity
            if (
                $this->profanity->blocker($titre)->filter() !== $titre ||
                $this->profanity->blocker($description)->filter() !== $description
            ) {
                $this->addFlash('error', 'The title or description contains profane language. Please remove it.');
                return $this->renderForm('conversation/index.html.twig', [
                    'conversations' => $conversationRepository->findAll(),
                    'form' => $createForm,
                    'user'=>$user,
                   
                ]);
            }
            $entityManager->persist($conversation);
            $entityManager->flush();
            $this->addFlash('success', ' Added successfully');
            return $this->redirectToRoute('app_conversation_index');
        }
        return $this->renderForm('conversation/new.html.twig', [
            'conversation' => $conversation,
            'form' => $createForm,
            'user'=>$user,
        ]);
    }

    #[Route('/{id}', name: 'app_conversation_show', methods: ['GET'])]
    public function show(Conversation $conversation): Response
    {
        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conversation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conversation $conversation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conversation/edit.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_conversation_delete')]
    public function delete($id, EntityManagerInterface $entityManager, ConversationRepository $conversationRepository): Response
    {
        $conv = $conversationRepository->find($id);
        
        if (!$conv) {
            throw $this->createNotFoundException('Conversation not found');
        }
    
        $entityManager->remove($conv);
        $entityManager->flush();
        $this->addFlash('success', ' deleted successfully');
        return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/like/{id}', name: 'app_conversation_like', methods: ['POST','GET'])]
    public function likeConversation(ConversationRepository $conversationRepository, UtilisateurRepository $utilisateurRepository, Request $request, EntityManagerInterface $entityManager,Conversation $conversation, EntityManagerInterface $em, SessionInterface $session,$id)
    {
        $conversation = new Conversation();
        $user = $utilisateurRepository->findOneBy(['id' => 56]);
        $currentDate = new \DateTime();
        $conversation->setIdUser($user);
        $conversation->setDate($currentDate);
        $conv=new Conversation();
      
        $form1 = $this->createForm(ConversationType::class, $conv);
        $form1->handleRequest($request);

        
        if ($form1->isSubmitted() && $form1->isValid()) {

            $titre1 = $form1->get('titre')->getData();
            $description1 = $form1->get('description')->getData();

            if (
                $this->profanity->blocker($titre1)->filter() !== $titre1 ||
                $this->profanity->blocker($description1)->filter() !== $description1
            ) {
                pnotify()->addWarning("'The title or description contains profane language. Please remove it.'");
                return $this->renderForm('conversation/index.html.twig', [
                    'conversations' => $conversationRepository->findAll(),
                    'form1'=>$form1
                    
                ]);
            }
            $convID = $request->request->get('reclamation_id');
            $convv = $conversationRepository->find($convID);
            $convv->setDate($currentDate);
            $convv->setDescription($description1);
            $convv->setTitre($titre1);
            $entityManager->flush();

            
            

            return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
        }
        $sessionKey = 'liked_conversations'; // Session key for storing liked conversations

        // Get the list of liked conversations from the session, initializing if needed
        $likedConversations = $session->get($sessionKey, []);
        $cv = $conversationRepository->findOneBy(['id' => $id]);
        // Check if the user has already liked this conversation
        if (in_array($cv->getId(), $likedConversations)) {
            pnotify()->addWarning('Already liked !');
        }else {
            $cv->setLikes($cv->getLikes() + 1);
            $likedConversations = array_diff($likedConversations, [$cv->getId()]); // Remove the ID
            $session->set($sessionKey, $likedConversations);

            $em->flush($cv);
            pnotify()->addSuccess('liked !');
        }

        // Store the conversation ID in the session
        $likedConversations[] = $cv->getId();
        $session->set($sessionKey, $likedConversations);

        
        return $this->renderForm('conversation/index.html.twig', [
            'conversations' => $conversationRepository->findAll(),
            'form1'=>$form1
        ]);
    }

    #[Route('/Dislike/{id}', name: 'app_conversation_dislike', methods: ['POST','GET'])]
    public function dislikeConversation(ConversationRepository $conversationRepository, UtilisateurRepository $utilisateurRepository, Request $request, EntityManagerInterface $entityManager,Conversation $conversation, EntityManagerInterface $em, SessionInterface $session,$id)
    {
        $conversation = new Conversation();
        $user = $utilisateurRepository->findOneBy(['id' => 56]);
        $currentDate = new \DateTime();
        $conversation->setIdUser($user);
        $conversation->setDate($currentDate);

       
        $conv=new Conversation();

        $form1 = $this->createForm(ConversationType::class, $conv);
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {

            $titre1 = $form1->get('titre')->getData();
            $description1 = $form1->get('description')->getData();

            if (
                $this->profanity->blocker($titre1)->filter() !== $titre1 ||
                $this->profanity->blocker($description1)->filter() !== $description1
            ) {
                pnotify()->addWarning("'The title or description contains profane language. Please remove it.'");
                return $this->renderForm('conversation/index.html.twig', [
                    'conversations' => $conversationRepository->findAll(),
                    'form1'=>$form1
                    
                ]);
            }
            $convID = $request->request->get('reclamation_id');
            $convv = $conversationRepository->find($convID);
            $convv->setDate($currentDate);
            $convv->setDescription($description1);
            $convv->setTitre($titre1);
            $entityManager->flush();
            return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
        }

        $sessionKey = 'liked_conversations'; // Session key for storing liked conversations

        $likedConversations = $session->get($sessionKey, []);

        $cv = $conversationRepository->findOneBy(['id' => $id]);

        if (in_array($cv->getId(), $likedConversations)) {
            // If the conversation is in the session, decrement likes and remove the ID
            $cv->setLikes($cv->getLikes() - 1);
            $likedConversations = array_diff($likedConversations, [$cv->getId()]); // Remove the ID from the array
            $session->set($sessionKey, $likedConversations); // Update the session
            $em->flush($cv); 
            pnotify()->addWarning("Like removed!");
        }
        return $this->renderForm('conversation/index.html.twig', [
            'conversations' => $conversationRepository->findAll(),
            'form1'=>$form1
        ]);
    }
}
