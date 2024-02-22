<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\NFT;
use App\Form\CartType;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartRepository $cartRepository): Response
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $cartRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_cart_new', methods: ['GET'])] 
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $sessionId = $session->getId();
        $id = $request->query->get('id'); 

        $nft = $entityManager->getRepository(NFT::class)->find($id);
        if (!$nft) {
            return $this->redirectToRoute('error_page');
        }
    
        $existingCart = $entityManager->getRepository(Cart::class)->findOneBy(['session_id' => $sessionId]);
    
        if ($existingCart) {
            $existingCart->addRelation($nft);
            $cart = $existingCart;
        } else {
            $cart = new Cart();
            $cart->addRelation($nft);
            $cart->setSessionId($sessionId);
            $entityManager->persist($cart);
        }
    
        $entityManager->flush();
    
        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route('/{id}', name: 'app_cart_show', methods: ['GET'])]
    public function show(Cart $cart): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cart/edit.html.twig', [
            'cart' => $cart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cart_delete', methods: ['POST'])]
    public function delete(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }
}
