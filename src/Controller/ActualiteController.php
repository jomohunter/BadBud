<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Actualite;
use App\Entity\Commentaire;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/actualite')]
class ActualiteController extends AbstractController
{
    #[Route('/', name: 'app_actualite')]
    public function index(): Response
    {
        return $this->render('actualite/index.html.twig');
    }

    #[Route('/afficher', name: 'app_afficher_actualite')]
    public function afficherActualite(ActualiteRepository $actualiteRepository): Response
    {
        $actualites = $actualiteRepository->findAll();
       
        return $this->render('actualite/listesActualites.html.twig', [
            'actualites' => $actualites,
        ]);
    }

  
    

   #[Route('/afficherB', name: 'app_afficher_actualitee')]
public function afficherActualitee(ActualiteRepository $actualiteRepository, CommentaireRepository $commentaireRepository): Response
{
    $actualites = $actualiteRepository->findAll();

    // Fetch commentaires related to each actualite
    foreach ($actualites as $actualite) {
        $commentaires = $commentaireRepository->findBy(['actualite' => $actualite]);
        $actualite->getCommentaire($commentaires);
    }

    return $this->render('actualite/listesActualitesB.html.twig', [
        'actualites' => $actualites,
    ]);
}

   
    
    #[Route('/ajouter', name: 'app_ajouter_actualite')]
    public function ajouterActualite(Request $request, EntityManagerInterface $em): Response
    {
        $actualite = new Actualite(); 
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->persist($actualite);
            $em->flush();
            
            return $this->redirectToRoute('app_afficher_actualitee');
        }
        
        return $this->render('actualite/ajouterActualite.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/modifier/{id}", name: "app_modifier_actualite")]
    public function modifierActualite(Request $request, Actualite $actualite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_afficher_actualitee');
        }
        
        return $this->render('actualite/modifierActualite.html.twig', [
            'form' => $form->createView()
        ]);
    }


#[Route("/supprimer/{id<\d+>}", name: "app_supprimer_actualite")]
public function supprimerActualite(Actualite $actualite, EntityManagerInterface $em, Request $request): Response
{
    $form = $this->createFormBuilder()
        ->add('confirm', SubmitType::class, ['label' => 'Confirm'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->remove($actualite);
        $em->flush();

        return $this->redirectToRoute('app_afficher_actualitee');
    }

    return $this->render('actualite/confirmation_delete.html.twig', [
        'actualite' => $actualite,
        'form' => $form->createView(),
    ]);
}
#[Route("/supprimer-commentaire/{id<\d+>}", name: "app_supprimer_commentaire")]
public function supprimerCommentaire(Commentaire $commentaire, EntityManagerInterface $em, Request $request): Response
{
    $actualiteId = $commentaire->getActualite()->getId();
    
    $form = $this->createFormBuilder()
        ->add('confirm', SubmitType::class, ['label' => 'Confirm Delete'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->remove($commentaire);
        $em->flush();
        $this->addFlash('success', 'Commentaire supprimé avec succès.');
        return $this->redirectToRoute('app_afficher_actualitee', ['id' => $actualiteId]);
    }

    return $this->render('commentaire/confirm_delete.html.twig', [
        'commentaire' => $commentaire,
        'form' => $form->createView(),
    ]);
}



}
