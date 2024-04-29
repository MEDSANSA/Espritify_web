<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Entity\Utilisateur;
use App\Form\EvenementType;
use App\Repository\ClubRepository;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(Request $request, ClubRepository $clubRepository, EvenementRepository $evenementRepository): Response
    {
        // Récupérer les paramètres de recherche depuis la requête
        $searchQuery = $request->query->get('search_query');
        $clubIntitule = $request->query->get('club_intitule');

        // Récupérer les paramètres de tri depuis la requête
        $sortField = $request->query->get('sort_field', 'titre');
        $sortOrder = $request->query->get('sort_order', 'asc');

        // Initialiser une variable pour stocker les événements
        $events = [];

        // Filtrer les événements par club si un club est spécifié
        if ($clubIntitule) {
            $club = $clubRepository->findOneBy(['intitule' => $clubIntitule]);
            if ($club) {
                $events = $evenementRepository->findEventsByClub($club->getId());
            }
        } else {
            // Sinon, récupérer tous les événements
            $events = $evenementRepository->findAll();
        }

        // Si un terme de recherche est fourni, effectuer une recherche
        if ($searchQuery) {
            $filteredEvents = [];
            foreach ($events as $event) {
                // Ajoutez ici les conditions de recherche appropriées, par exemple sur le titre
                if (
                    stripos($event->getTitre(), $searchQuery) !== false ||
                    stripos($event->getDescription(), $searchQuery) !== false ||
                    stripos($event->getLieu(), $searchQuery) !== false ||
                    stripos($event->getDateDebut()->format('m/d/Y'), $searchQuery) !== false ||
                    stripos($event->getDateFin()->format('Y-m-d'), $searchQuery) !== false


                )  {
                    $filteredEvents[] = $event;
                }
            }
            $events = $filteredEvents;
        }

        // Trier les événements en fonction des paramètres de tri
        if ($sortField === 'id' || $sortField === 'dateDebut' || $sortField === 'titre') {
            usort($events, function ($a, $b) use ($sortField, $sortOrder) {
                $valueA = $a->{'get' . ucfirst($sortField)}();
                $valueB = $b->{'get' . ucfirst($sortField)}();

                if ($valueA == $valueB) {
                    return 0;
                }

                if ($sortOrder === 'asc') {
                    return ($valueA < $valueB) ? -1 : 1;
                } else {
                    return ($valueA > $valueB) ? -1 : 1;
                }
            });
        }
        // Passer les événements récupérés au template Twig avec les paramètres de recherche pour affichage
        return $this->render('Admin/evenement/index.html.twig', [
            'events' => $events,
            'searchQuery' => $searchQuery,
            'clubIntitule' => $clubIntitule,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'L Evènement a été ajouté avec succès !');

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Admin/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('Admin/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'L Evènement a été modifié avec succès !');

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Admin/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'L Evènement a été supprimé avec succès !');

        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }






}
