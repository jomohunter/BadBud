<?php

namespace App\Controller;

use App\Controller\Notification;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
#use Symfony\Component\Notifier\TexterInterface; TexterInterface $texter /UserRepository $repo,



class LoginController extends AbstractController
{
    #[Route('/afterlogin', name: 'afterlogin')]
    public function test( Security $security): Response
    {
        
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('Read_User');
        }
        if ($security->isGranted('ROLE_USER')) {
            return $this->render('login/login_user.html.twig');
        }
        #return $this->redirectToRoute('app_login');
    }

    #[Route('/Read', name: 'Read_User')]
    public function ReadUser(UserRepository $repo, Security $security): Response
    {   #$notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $Users = $repo->findAll();
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->render('login/login_admin.html.twig', [
                'users' => $Users,
            ]);
        }
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('afterlogin');
        }
        return $this->redirectToRoute('app_login');
    }
}
