<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use App\Security\Voter\ClientVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route(name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        if (!$this->isGranted(ClientVoter::INDEX)) {
            $this->addFlash('error', 'client.access_denied_index');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ClientVoter::NEW)) {
            $this->addFlash('error', 'client.access_denied_new');

            return $this->redirectToRoute('app_home');
        }

        $client = new Client();
        $isEdit = false;
        $form = $this->createForm(ClientFormType::class, $client, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setCreatedAt(new \DateTime());
            
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'client.created');

            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/new.html.twig', [
            'form'   => $form,
            'isEdit' => $isEdit,
            'client' => $client,
        ]);
    }

    #[Route('/{id}/show', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        if (!$this->isGranted(ClientVoter::SHOW)) {
            $this->addFlash('error', 'client.access_denied_show');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ClientVoter::EDIT)) {
            $this->addFlash('error', 'client.access_denied_edit');

            return $this->redirectToRoute('app_home');
        }

        $isEdit = true;
        $form = $this->createForm(ClientFormType::class, $client, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'client.edited');

            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/edit.html.twig', [
            'form'   => $form,
            'isEdit' => $isEdit,
            'client' => $client,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_client_delete')]
    public function delete(Client $client, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ClientVoter::DELETE)) {
            $this->addFlash('error', 'client.access_denied_delete');

            return $this->redirectToRoute('app_home');
        }

        $entityManager->remove($client);
        $entityManager->flush();

        $this->addFlash('success', 'client.deleted');

        return $this->redirectToRoute('app_client_index');
    }
}
