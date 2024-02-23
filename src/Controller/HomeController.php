<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(UserRepository $repo, Security $security, AuthenticationUtils $authenticationUtils): Response
    {   
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $Users = $repo->findAll();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $this->getUser();
        
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->render('login/login_admin.html.twig', [
                'users' => $Users
            ]);
        }

        if ($security->isGranted('ROLE_USER')) {
            return $this->render('login/login_user.html.twig');
        }

        return $this->render('security/sign_in.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);    
    }
}
