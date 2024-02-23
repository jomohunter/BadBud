<?php

namespace App\Controller;
use App\Entity\Projets;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\NFT;
use App\Form\NFTType;
use App\Repository\NFTRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nft')]
class NFTController extends AbstractController
{
    #[Route('/', name: 'app_n_f_t_index', methods: ['GET'])]
    public function index(NFTRepository $nFTRepository): Response
    {
        return $this->render('nft/index.html.twig', [
            'nfts' => $nFTRepository->findAll(),
        ]);
    }

    #[Route('/new/{projectId}', name: 'app_n_f_t_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , $projectId): Response
    {
        $nFT = new NFT();
        $form = $this->createForm(NFTType::class, $nFT);
        $form->handleRequest($request);
        $project = $entityManager->getRepository(Projets::class)->find($projectId);
        if (!$project) {
            throw $this->createNotFoundException('Le projet associé n\'existe pas');
        }
        

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('image')->getData();

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
                $nFT->setImage($newFilename);
            }
            $nft->set($project);
            
            $entityManager->persist($nFT);
            $entityManager->flush();

            return $this->redirectToRoute('app_n_f_t_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nft/new.html.twig', [
            'nft' => $nFT,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_n_f_t_show', methods: ['GET'])]
    public function show(NFT $nFT): Response
    {
        return $this->render('nft/show.html.twig', [
            'nft' => $nFT,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_n_f_t_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NFT $nFT, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NFTType::class, $nFT);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_n_f_t_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('nft/edit.html.twig', [
            'nft' => $nFT,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_n_f_t_delete', methods: ['POST'])]
    public function delete(Request $request, NFT $nFT, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nFT->getId(), $request->request->get('_token'))) {
            $entityManager->remove($nFT);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_n_f_t_index', [], Response::HTTP_SEE_OTHER);
    }
}
