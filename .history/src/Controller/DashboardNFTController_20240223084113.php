<?php

namespace App\Controller;

use App\Entity\NFT;
use App\Form\NFT1Type;
use App\Repository\NFTRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/nft')]
class DashboardNFTController extends AbstractController
{
    #[Route('/', name: 'app_dashboard_n_f_t_index', methods: ['GET'])]
    public function index(NFTRepository $nFTRepository): Response
    {
        return $this->render('dashboard_nft/index.html.twig', [
            'nfts' => $nFTRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dashboard_n_f_t_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nFT = new NFT();
        $form = $this->createForm(NFT1Type::class, $nFT);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nFT);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_n_f_t_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard_nft/new.html.twig', [
            'nft' => $nFT,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_n_f_t_show', methods: ['GET'])]
    public function show(NFT $nFT): Response
    {
        return $this->render('dashboard_nft/show.html.twig', [
            'nft' => $nFT,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dashboard_n_f_t_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NFT $nFT, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NFT1Type::class, $nFT);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_n_f_t_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard_nft/edit.html.twig', [
            'n_f_t' => $nFT,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_n_f_t_delete', methods: ['POST'])]
    public function delete(Request $request, NFT $nFT, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nFT->getId(), $request->request->get('_token'))) {
            $entityManager->remove($nFT);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_n_f_t_index', [], Response::HTTP_SEE_OTHER);
    }
}
