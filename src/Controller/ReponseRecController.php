<?php

namespace App\Controller;

use App\Entity\ReponseRec;
use App\Form\ReponseRecType;
use App\Repository\ReponseRecRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponse/rec')]
class ReponseRecController extends AbstractController
{
    #[Route('/', name: 'app_reponse_rec_index', methods: ['GET'])]
    public function index(ReponseRecRepository $reponseRecRepository): Response
    {
        return $this->render('reponse_rec/index.html.twig', [
            'reponse_recs' => $reponseRecRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reponse_rec_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponseRec = new ReponseRec();
        $form = $this->createForm(ReponseRecType::class, $reponseRec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponseRec);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_rec_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse_rec/new.html.twig', [
            'reponse_rec' => $reponseRec,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_rec_show', methods: ['GET'])]
    public function show(ReponseRec $reponseRec): Response
    {
        return $this->render('reponse_rec/show.html.twig', [
            'reponse_rec' => $reponseRec,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_rec_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReponseRec $reponseRec, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseRecType::class, $reponseRec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_rec_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse_rec/edit.html.twig', [
            'reponse_rec' => $reponseRec,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_rec_delete', methods: ['POST'])]
    public function delete(Request $request, ReponseRec $reponseRec, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponseRec->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponseRec);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_rec_index', [], Response::HTTP_SEE_OTHER);
    }
}
