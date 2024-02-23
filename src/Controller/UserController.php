<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('login/login_admin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile', name: 'profile_user')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig');
    }


    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    // ...  
    #[Route('/ban/{id}', name: 'banUser')]

    public function banUser(EntityManagerInterface $entityManager, $id): Response
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }
        if ($currentUser->getRoles() == ["ROLE_ADMIN"]) {
            $user = $entityManager->getRepository(User::class)->find($id);
            $user->setIsBanned(true);
            $entityManager->flush();

            return $this->redirectToRoute('all_users');
        } else {
            return $this->redirectToRoute('Read_User');
        }
    }

    #[Route('/unban/{id}', name: 'unban')]
    public function unbanUser(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $user = $entityManager->getRepository(User::class)->find($id);
            $user->setIsBanned(false);
            $entityManager->flush();

            return $this->redirectToRoute('all_users');
        } else {
            return $this->redirectToRoute('Read_User');
        }
    }

    #[Route('/User/Status/{id}', name: 'Status')]
    public function DisableOrEnableUser(EntityManagerInterface $em, $id): Response
    {
        $repo = $em->getRepository(User::class);
        $User = $repo->find($id);
    
        if ($User->getStatus() === 'enabled') {
            $User->setStatus('disabled');
        } elseif ($User->getStatus() === 'disabled') {
            $User->setStatus('enabled');
        }
    
        $em->persist($User);
        $em->flush();
    
        return $this->redirectToRoute('Read_User');
    }


}
