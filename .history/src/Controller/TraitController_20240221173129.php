<?php

namespace App\Controller;

use App\Entity\Traits;
use App\Entity\Projets;
use App\Form\Traits1Type;
use App\Form\TraitsType;
use App\Repository\TraitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/trait')]
class TraitController extends AbstractController
{
    #[Route('/', name: 'app_trait_index', methods: ['GET'])]
    public function index(TraitsRepository $traitsRepository): Response
    {
        $traits = $traitsRepository->findAll();
        $traitsbytype = [];

        foreach($traits as $trait) {
            $traitsbytype[$trait->getTypeDeTrait()][] = $trait;
        }

        return $this->render('trait/index.html.twig', [
            'traitsbytype' => $traitsbytype,
        ]);

    }

    #[Route('/new', name: 'app_trait_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $id = $request->query->get('id');

        $project = $entityManager->getRepository(projets::class)->find($id);
        $trait = new Traits();
        
        $trait->setProjets($project);


        $form = $this->createForm(TraitsType::class, $trait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trait);
            $entityManager->flush();

            return $this->redirectToRoute('app_trait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trait/new.html.twig', [
            'trait' => $trait,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trait_show', methods: ['GET'])]
    public function show(Traits $trait): Response
    {
        return $this->render('trait/show.html.twig', [
            'trait' => $trait,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trait_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Traits $trait, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Traits1Type::class, $trait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trait/edit.html.twig', [
            'trait' => $trait,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trait_delete', methods: ['POST'])]
    public function delete(Request $request, Traits $trait, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trait->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trait_index', [], Response::HTTP_SEE_OTHER);
    }
}
