<?php

namespace App\Controller;

use GuzzleHttp\Client;
use App\Entity\Questions;
use App\Entity\Quizz;
use App\Form\QuizzType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Laracasts\Flash\Flash;

#[Route('/quizzback')]
class QuizzController extends AbstractController
{
    #[Route('/', name: 'app_quizz_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subject = $request->query->get('subject');
        $searchTerm = $request->query->get('q');
        $sortBy = $request->query->get('sort_by', 'idQuizz');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $queryBuilder = $entityManager->getRepository(Quizz::class)->createQueryBuilder('q');

        if ($subject) {
            $queryBuilder->andWhere('q.sujet = :subject')
                ->setParameter('subject', $subject);
        }

        if ($searchTerm) {
            $queryBuilder->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('q.sujet', ':searchTerm'),
                $queryBuilder->expr()->like('q.description', ':searchTerm')
            ))->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $queryBuilder->orderBy('q.' . $sortBy, $sortOrder);

        $quizzs = $queryBuilder->getQuery()->getResult();

        $uniqueSubjects = [];
        foreach ($quizzs as $quizz) {
            $uniqueSubjects[$quizz->getSujet()] = $quizz->getSujet();
        }

        return $this->render('quizz/index.html.twig', [
            'quizzs' => $quizzs,
            'searchTerm' => $searchTerm,
            'uniqueSubjects' => $uniqueSubjects,
        ]);
    }

    #[Route('/quizzs/filter', name: 'quizz_filter', methods: ['GET'])]
    public function filterQuizzs(EntityManagerInterface $entityManager, Request $request): Response
    {
        $subject = $request->query->get('subject');

        $quizzs = $entityManager->getRepository(Quizz::class)->findBy(['sujet' => $subject]);

        return new JsonResponse($this->serializeQuizzs($quizzs));
    }

    private function serializeQuizzs($quizzs)
    {
        $data = [];
        foreach ($quizzs as $quizz) {
            $data[] = [
                'idQuizz' => $quizz->getIdQuizz(),
                'sujet' => $quizz->getSujet(),
                'description' => $quizz->getDescription(),
                'idQuestion' => $quizz->getIdQuestion(),
            ];
        }
        return $data;
    }
    //guzzle-bundle and trivia api
    #[Route('/other', name: 'app_other_quizzes')]
    public function fetchTriviaQuestions(Request $request): Response
    {
        $category = $request->query->get('category');

        $client = new Client([
            'base_uri' => 'https://opentdb.com/',
            'timeout'  => 3.0,
        ]);

        $response = $client->request('GET', 'api.php', [
            'query' => [
                'amount' => 20,
                'category' => $category, // General Knowledge
                'type' => 'multiple',
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $this->render('Front/trivia.html.twig', ['questions' => $data['results']]);
    }

    #[Route('/quiz/submit', name: 'trivia_quiz_submit', methods: ['POST'])]
    public function submitTriviaQuiz(Request $request): Response
    {
        $formData = $request->request->all();

        $client = new Client([
            'base_uri' => 'https://opentdb.com/',
            'timeout'  => 3.0,
        ]);

        $response = $client->request('GET', 'api.php', [
            'query' => [
                'amount' => 20,
                'category' => 9,
                'type' => 'multiple',
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['results']) && !empty($data['results'])) {
            $totalQuestions = count($formData);
            $correctAnswers = 0;

            foreach ($formData as $userQuestionId => $userAnswer) {
                foreach ($data['results'] as $question) {
                    if ($userAnswer == $question['correct_answer']) {
                        $correctAnswers++;
                        break;
                    }
                }
            }
            $percentage = ($correctAnswers / $totalQuestions) * 100;
            $integerPart = (int)$percentage;

            return $this->render('Front/result.html.twig', [
                'score' => $correctAnswers,
                'percentage' => $integerPart,
                'data' => $data
            ]);
        }
    }


    //front controller
    #[Route('/quizz', name: 'app_quizz', methods: ['GET'])]
    public function Quizz(EntityManagerInterface $entityManager)
    {
        $quizz = $entityManager->getRepository(Quizz::class)->findAll();

        return $this->render('Front/quizz.html.twig', [
            'quizz' => $quizz,
        ]);
    }

    #[Route('/quizzs', name: 'app_quizzs', methods: ['GET'])]
    public function startQuiz(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $quizz = $entityManager->getRepository(Quizz::class)->findAll();

        $currentQuizzIndex = $session->get('current_quizz_index', 0);
        $currentQuizz = $quizz[$currentQuizzIndex];
        $session->set('current_quizz_index', 0);
        $session->set('user_answers', []);

        return $this->render('Front/quizz.html.twig', [
            'quizzs' => $currentQuizz,
            'totalQuizz' => count($quizz),
            'currentQuizzIndex' => $currentQuizzIndex,
        ]);
    }

    #[Route('/next-question', name: 'next_question', methods: ['GET'])]
    public function nextQuestion(SessionInterface $session): Response
    {
        $currentQuizzIndex = $session->get('current_quizz_index', 0);
        $session->set('current_quizz_index', $currentQuizzIndex + 1);

        return $this->redirectToRoute('app_quizz', ['question' => $currentQuizzIndex + 1]);
    }

    #[Route('/previous-question', name: 'previous_question', methods: ['GET'])]
    public function previousQuestion(SessionInterface $session): Response
    {
        $currentQuizzIndex = $session->get('current_quizz_index', 0);
        $session->set('current_quizz_index', max(0, $currentQuizzIndex - 1));

        return $this->redirectToRoute('app_quizz', ['question' => $currentQuizzIndex - 1]);
    }

    #[Route('/quizz/submit', name: 'quiz_submit', methods: ['POST'])]
    public function submitQuiz(Request $request, EntityManagerInterface $entityManager)
    {
        $formData = $request->request->all();

        $result = $this->calculateScore($formData, $entityManager);
        $score = $result['score'];
        $percentage = $result['percentage'];
        $integerPart = (int)$percentage;

        return $this->render('Front/result.html.twig', [
            'score' => $score,
            'percentage' => $integerPart
        ]);
    }

    private function calculateScore($formData, EntityManagerInterface $entityManager)
    {
        $totalQuestions = count($formData);
        $score = 0;

        foreach ($formData as $questionId => $userAnswer) {
            $question = $entityManager->getRepository(Questions::class)->find($questionId);

            if ($question !== null) {
                $correctAnswer = $question->getBonRep();

                if ($userAnswer === $correctAnswer) {
                    $score++;
                }
            }
        }

        $percentage = ($score / $totalQuestions) * 100;
        $percentage = round($percentage, 2);

        return [
            'score' => $score,
            'percentage' => $percentage
        ];
    }

    //backend controller
    #[Route('/new', name: 'app_quizz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quizz = new Quizz();
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quizz);
            $entityManager->flush();

            $this->addFlash('success', 'Ajout avec succès!');

            return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quizz/new.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{idQuizz}', name: 'app_quizz_show', methods: ['GET'])]
    public function show(Quizz $quizz): Response
    {
        return $this->render('quizz/show.html.twig', [
            'quizz' => $quizz,
        ]);
    }

    #[Route('/{idQuizz}/edit', name: 'app_quizz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Edit avec succès!');

            return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quizz/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{idQuizz}', name: 'app_quizz_delete', methods: ['POST'])]
    public function delete(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quizz->getIdQuizz(), $request->request->get('_token'))) {
            $entityManager->remove($quizz);
            $entityManager->flush();
            $this->addFlash('success', 'Delete avec succès!');
        }

        return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
    }
}
