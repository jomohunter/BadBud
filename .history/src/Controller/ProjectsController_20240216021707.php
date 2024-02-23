<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Projets;
use App\Form\ProjectsType;
use App\Repository\ProjetsRepository;
use Doctrine\ORM\EntityManagerInterface;



#[Route('/projects')]
class ProjectsController extends AbstractController
{
    #[Route('/', name: 'app_projects', methods: ['GET'])]
    public function index(ProjetsRepository $projetsRepository): Response
    {
        return $this->render('projects/index.html.twig', [
            'projects' => $projetsRepository->findAll(),
        ]);
    }

        #[Route('/new', name: 'new_project', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Projets();
        if(!$project->getDateDeCreation()){
        $project->setDateDeCreation(new \DateTime());}

        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_projects', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projects/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'show_project', methods: ['GET'])]
    public function show(Projets $projets): Response
    {
        return $this->render('projects/show.html.twig', [
            'project' => $projets,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit_projects', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectsType::class, $projets);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_projects', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projects/edit.html.twig', [
            'projet' => $projets,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_n_f_t_delete', methods: ['POST'])]
    public function delete(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projets->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projets);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_n_f_t_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/', name: 'app_projects', methods: ['GET'])]
    public function indexBack(ProjetsRepository $projetsRepository): Response
    {
        return $this->render('projects/index.html.twig', [
            'projects' => $projetsRepository->findAll(),
        ]);
    }

        #[Route('/new', name: 'new_project', methods: ['GET', 'POST'])]
    public function newBack(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Projets();
        if(!$project->getDateDeCreation()){
        $project->setDateDeCreation(new \DateTime());}

        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_projects', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projects/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'show_project', methods: ['GET'])]
    public function showBack(Projets $projets): Response
    {
        return $this->render('projects/show.html.twig', [
            'project' => $projets,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit_projects', methods: ['GET', 'POST'])]
    public function editBack(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectsType::class, $projets);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_projects', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projects/edit.html.twig', [
            'projet' => $projets,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_n_f_t_delete', methods: ['POST'])]
    public function deleteBack(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projets->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projets);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_n_f_t_index', [], Response::HTTP_SEE_OTHER);
    }
}
