<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuestionsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions')]
class QuestionsController extends AbstractController
{
    #[Route('/', name: 'app_questions_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $questions = $entityManager
            ->getRepository(Questions::class)
            ->findAll();

        return $this->render('questions/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/new', name: 'app_questions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Questions();
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questions/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{idQuestion}', name: 'app_questions_show', methods: ['GET'])]
    public function show(Questions $question): Response
    {
        return $this->render('questions/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{idQuestion}/edit', name: 'app_questions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Questions $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questions/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{idQuestion}', name: 'app_questions_delete', methods: ['POST'])]
    public function delete(Request $request, Questions $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getIdQuestion(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
    }
}
