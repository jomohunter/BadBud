<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
    
  
    #[Route('/afficher/{id}', name: 'app_afficher_com')]
    public function afficherActualiteEtCommentaires(
        $id, 
        ActualiteRepository $actualiteRepository, 
        CommentaireRepository $commentaireRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $actualite = $actualiteRepository->find($id);

        if (!$actualite) {
            throw $this->createNotFoundException('Actualite not found');
        }

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setActualite($actualite);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_afficher_com', ['id' => $id]);
        }

        $commentaires = $commentaireRepository->findBy(['actualite' => $actualite]);

        return $this->render('commentaire/listesCommentaires.html.twig', [
            'actualite' => $actualite,
            'commentaires' => $commentaires,
            'form' => $form->createView()
        ]);
    }

    

    #[Route('/afficherB/{id}', name: 'app_afficher_comB')]
    public function afficherCommentairesB($id, ActualiteRepository $actualiteRepository, CommentaireRepository $commentaireRepository): Response
    {
        $actualite = $actualiteRepository->find($id);
    
        if (!$actualite) {
            throw $this->createNotFoundException('Actualite not found');
        }
    
        $commentaires = $commentaireRepository->findBy(['actualite' => $actualite]);
    
        return $this->render('commentaire/listesCommentaireB.html.twig', [
            'actualite' => $actualite,
            'commentaires' => $commentaires,
        ]);
    }
    

    #[Route('/ajouter-commentaire', name: 'app_ajouter_commentaire')]
    public function ajouterCommentaire(Request $request, EntityManagerInterface $em): Response
    {
        $commentaire = new Commentaire(); 
        $commentaire->setDateContenu(new \DateTime()); // Set the date_contenu field to the current date and time
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->persist($commentaire);
            $em->flush();
            
            return $this->redirectToRoute('app_afficher_commentaire_b');
        }
        
        return $this->render('commentaire/ajouterCommentaire.html.twig', [
            'form' => $form->createView()
        ]);
    }



  
    
    
    
    

}