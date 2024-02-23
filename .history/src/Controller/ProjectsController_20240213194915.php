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
            'projects' => 'ProjectsController',
        ]);
    }

        #[Route('/new', name: 'app_n_f_t_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Projets();
        $form = $this->createForm(NFTType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_n_f_t_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nft/new.html.twig', [
            'n_f_t' => $nFT,
            'form' => $form,
        ]);
    }
}
