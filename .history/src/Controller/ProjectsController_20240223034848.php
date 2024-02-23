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
use Symfony\Component\Validator\Constraints as Assert; 


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
            $photoFile = $form->get('photoURL')->getData();

            // Vérifiez si un fichier a été téléchargé
            if ($photoFile) {
                // Générez un nom de fichier unique
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                // Déplacez le fichier vers le répertoire où sont stockées les photos
                $photoFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );

                // Mettez à jour l'URL de l'image dans l'entité Project
                $project->setPhotoUrl($newFilename);
            }
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
            'project' => $projets,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete_projet', methods: ['POST'])]
    public function delete(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projets->getId(), $request->request->get('_token'))) {

            $traits = $projets->getTraits();

            foreach($traits as $trait){
                $entityManager->remove($trait);
            }
            
            $entityManager->remove($projets);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projects', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/dashboard', name: 'dashboard_projects_index', methods: ['GET'])]
    public function dashboardIndex(ProjetsRepository $projetsRepository): Response
    {
        return $this->render('dashboard/projects/index.html.twig', [
            'projects' => $projetsRepository->findAll(),
        ]);
    }

    #[Route('/dashboard/new', name: 'dashboard_new_project', methods: ['GET', 'POST'])]
    public function dashboardNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Projets();
        if (!$project->getDateDeCreation()) {
            $project->setDateDeCreation(new \DateTime());
        }

        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoURL')->getData();

            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                $photoFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );
                $project->setPhotoUrl($newFilename);
            }
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/projects/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/{id}', name: 'dashboard_show_project', methods: ['GET'])]
    public function dashboardShow(Projets $projets): Response
    {
        return $this->render('dashboard/projects/show.html.twig', [
            'project' => $projets,
        ]);
    }

    #[Route('/dashboard/{id}/edit', name: 'dashboard_edit_project', methods: ['GET', 'POST'])]
    public function dashboardEdit(Request $request, Projets $projets, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectsType::class, $projets);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/projects/edit.html.twig', [
            'project' => $projets,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/{id}/delete', name: 'dashboard_delete_project', methods: ['POST'])]
    public function dashboardDelete(Request $request, Projets $projets, EntityManagerInterface $entityManager ,  Projets $project): Response
    {
        $nfts = $project->getNft();
    
        foreach ($nfts as $nft) {
        $entityManager->remove($nft);
        }
    
        $entityManager->remove($project);
        $entityManager->flush();

        return $this->redirectToRoute('app_projects');
    }

    



   
}
