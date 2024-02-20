<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\NFT;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class CommandeController extends AbstractController
{
    #[Route('/commande/add/{id}', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $commande = new Commande();

        $nft = $entityManager->getRepository(NFT::class)->find($id);

        $form = $this->createForm(CommandeType::class, $commande, [
            'nft_price' => $nft->getPrice(),
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isvalid()){
            $entityManager->persist($commande);
            $entityManager->flush();

            $nft->setCommande($commande);
            $entityManager->persist($nft);
            $entityManager->flush();

            return $this->redirectToRoute('app_Commande', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/add.html.twig', [
            'nft' => $nft,
            'commande' => $commande,
            'form' => $form,
        ]);

    }


    #[Route('/commande/show', name: 'app_Commande', methods: ['GET'])]
    public function show(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/Commande.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }


    #[Route('/commande/edit/{id}', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager, int $id): Response
    {
       
        $nft =   $entityManager->getRepository(NFT::class)->find($id);


        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setTotal($nft->getPrice());

            $entityManager->flush();

            return $this->redirectToRoute('app_Commande', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'nft' => $nft,
            'commande' => $commande,
            'form' => $form,
        ]);
    }
    

    #[Route('/commande/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $cmd, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmd->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cmd);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Commande', [], Response::HTTP_SEE_OTHER);
    }
}
