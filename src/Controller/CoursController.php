<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\CoursRepository;
use Laracasts\Flash\Flash;
//use Stichoza\GoogleTranslateBundle\StichozaGoogleTranslateBundle;



#[Route('/cours')]
class CoursController extends AbstractController
{
    #[Route('/', name: 'app_cours_index', methods: ['GET'])]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $sortby=$request->query->get('sortby' ,'title');
        $sortOrder=$request->query->get('sort_order' , 'asc');

        $queryBuilder= $entityManager ->getRepository(Cours::class) ->createQueryBuilder('c');
        $categorie = $entityManager
        ->getRepository(Categorie::class)
        ->findAll();
        $cours = $entityManager
            ->getRepository(Cours::class)
            ->findAll();

        $queryBuilder->orderBy('c.' . $sortby, $sortOrder);

        $cours=$queryBuilder->getQuery()->getResult();


        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'categorie' => $categorie,
        ]);
    }

    #[Route('/detail/{id}', name: 'cours_detail')]
public function coursdetail($id, EntityManagerInterface $entityManager): Response
{   
    $cours = $entityManager
        ->getRepository(Cours::class)
        ->find($id);

    if (!$cours) {
        throw $this->createNotFoundException('Course not found');
    }

    return $this->render('Front/coursDetail.html.twig', [
        'course' => $cours,
    ]);
}
    

    #[Route('/coursfront', name: 'app_cours')]
    public function cours(EntityManagerInterface $entityManager, PaginatorInterface $paginatorInterface, Request $req
    ): Response
    {
       
        $searchTerm = $req->query->get('q');
        if (!$searchTerm) {

        $cours = $entityManager
            ->getRepository(Cours::class)
            ->findAll();
        } else {
            $cours= $entityManager
                ->getRepository(cours::class)
                ->createQueryBuilder('q')
                ->where('q.title LIKE :searchTerm OR q.contenu LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }
        $cours = $paginatorInterface->paginate(
                $cours, /* query NOT result */
                $req->query->getInt('page', 1),
                3
            );
        return $this->render('Front/cours.html.twig', [
            'cours' => $cours,
           
        ]);
    }

    

    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cour = new Cours();
        
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contenuFile = $form['contenu']->getData();
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads'; 
            $contenuFileName = md5(uniqid()) . '.' . $contenuFile->guessExtension();
            $contenuFile->move($uploadsDirectory, $contenuFileName);
            $cour->setContenu($contenuFileName);
            $entityManager->persist($cour);
            $entityManager->flush();
            $this->addFlash('success', 'Course succesfully added.');

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {
        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/edit.html.twig', [
            'cours' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
            $this->addFlash('success', 'Course successfully deleted.');
            
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }
   
   
    
    #[Route('/CoursFilter', name: 'app_admin_cours_filter', methods:['GET'])]
    public function getCoursFilter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieId = $request->query->get('idCat');
        $courses = $entityManager->getRepository(Cours::class)->findBy(['idCat' => $categorieId]);
    
        $jsonData = $this->jsonCours($courses);
    
        return new JsonResponse($jsonData, JsonResponse::HTTP_OK, [], true);
    }
    
    public function jsonCours($cours)
    {
        $data = [];
        foreach ($cours as $cour) {
            // Assuming you have some properties you want to include in the response
            $data[] = [
                'titre' => $cour->getTitle(),
                'contenu' => $cour->getContenu(),
                'etat' => $cour->isEtat(),
                'rate' => $cour->getRate(),
                'categorie' => $cour->getIdCat()->getType(),
                // Add more properties as needed
            ];
        }
    
        return $data; // Return the array directly, JsonResponse will handle JSON encoding
    }

}
