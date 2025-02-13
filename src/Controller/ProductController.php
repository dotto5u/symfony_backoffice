<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Security\Voter\ProductVoter;
use App\Service\CsvService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        if (!$this->isGranted(ProductVoter::INDEX)) {
            $this->addFlash('error', 'product.access_denied_index');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->getByPriceDesc(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ProductVoter::NEW)) {
            $this->addFlash('error', 'product.access_denied_new');

            return $this->redirectToRoute('app_home');
        }

        $product = new Product();
        $isEdit = false;
        $form = $this->createForm(ProductFormType::class, $product, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash("success", "product.created");

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
            'product' => $product,
        ]);
    }

    #[Route('/{id}/show', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        if (!$this->isGranted(ProductVoter::SHOW)) {
            $this->addFlash('error', 'product.access_denied_show');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ProductVoter::EDIT)) {
            $this->addFlash('error', 'product.access_denied_edit');

            return $this->redirectToRoute('app_home');
        }

        $isEdit = true;
        $form = $this->createForm(ProductFormType::class, $product, ['is_edit' => $isEdit]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("success", "product.edited");

            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
            'product' => $product,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_product_delete')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted(ProductVoter::DELETE)) {
            $this->addFlash('error', 'product.access_denied_delete');

            return $this->redirectToRoute('app_home');
        }

        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash("success", "product.deleted");

        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/export', name: 'app_product_export')]
    public function export(ProductRepository $productRepository, CsvService $csvService): Response
    {
        if (!$this->isGranted(ProductVoter::EXPORT)) {
            $this->addFlash('error', 'product.access_denied_export');

            return $this->redirectToRoute('app_home');
        }

        $products = $productRepository->findAll();
        $csv = $csvService->exportProducts($products);
        $filename = "products.csv";
        
        $response = new Response($csv);
        $response->headers->set("Content-Type", "text/csv; charset=utf-8'");
        $response->headers->set("Content-Disposition", "attachment; filename=$filename");

        return $response;
    }
}
