<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {   
        if (!$this->isGranted(UserVoter::INDEX)) {
            $this->addFlash('error', 'user.access_denied_index');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {   
        if (!$this->isGranted(UserVoter::NEW)) {
            $this->addFlash('error', 'user.access_denied_new');

            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $isEdit = false;
        $form = $this->createForm(UserFormType::class, $user, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordHasherInterface->hashPassword($user, $user->getPassword()));
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("success", "user.created");

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
            'user' => $user
        ]);
    }

    #[Route('/{id}/show', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {   
        if (!$this->isGranted(UserVoter::SHOW)) {
            $this->addFlash('error', 'user.access_denied_show');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {       
        if (!$this->isGranted(UserVoter::EDIT)) {
            $this->addFlash('error', 'user.access_denied_edit');

            return $this->redirectToRoute('app_home');
        }

        $isEdit = true;
        $form = $this->createForm(UserFormType::class, $user, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("success", "user.edited");

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
            'user' => $user
        ]);
    }

    #[Route('/{id}/delete', name: 'app_user_delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {   
        if (!$this->isGranted(UserVoter::DELETE)) {
            $this->addFlash('error', 'user.access_denied_delete');

            return $this->redirectToRoute('app_home');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash("success", "user.deleted");

        return $this->redirectToRoute('app_user_index');
    }
}
