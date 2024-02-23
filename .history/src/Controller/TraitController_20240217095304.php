<?php

namespace App\Controller;

use App\Entity\Traits;
use App\Form\Traits1Type;
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
        return $this->render('trait/index.html.twig', [
            'traits' => $traitsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trait_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trait = new Traits();
        $form = $this->createForm(Traits1Type::class, $trait);
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

    #[Route('/{id}', name: '{% set grouped_traits = {} %}
    {% for trait in traits %}
        {% if trait.type_de_trait not in grouped_traits %}
            {% set grouped_traits = grouped_traits|merge({(trait.type_de_trait): [trait]}) %}
        {% else %}
            {% set _ = grouped_traits[trait.type_de_trait].append(trait) %}
        {% endif %}
    {% endfor %}
    
    {% for type, traits in grouped_traits %}
        <optgroup label="{{ type }}">
            {% for trait in traits %}
                <option value="{{ trait.id }}">{{ trait.name }}</option>
            {% endfor %}
        </optgroup>
    {% endfor %}', methods: ['GET'])]
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
