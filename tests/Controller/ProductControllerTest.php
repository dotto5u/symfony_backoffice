<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Controller\ProductController;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends TestCase
{
    public function testNouveauProduitAvecFormulaireValide(): void
    {
        $formStub = $this->createMock(FormInterface::class);
        $formStub->expects($this->once())
            ->method('handleRequest')
            ->with($this->isInstanceOf(Request::class));
        $formStub->method('isSubmitted')->willReturn(true);
        $formStub->method('isValid')->willReturn(true);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Product::class));
        $entityManagerMock->expects($this->once())
            ->method('flush');

        $controller = $this->getMockBuilder(ProductController::class)
            ->onlyMethods(['createForm', 'isGranted', 'addFlash', 'redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('createForm')
            ->willReturnCallback(function ($type, $product, $options) use ($formStub) {
                $product->setName('name')
                    ->setDescription('description')
                    ->setPrice('100.00');
                return $formStub;
            });

        $controller->method('isGranted')->willReturn(true);

        $controller->expects($this->once())
            ->method('addFlash')
            ->with('success', 'product.created');

        $expectedResponse = new RedirectResponse('/product');
        $controller->method('redirectToRoute')
            ->with('app_product_index')
            ->willReturn($expectedResponse);

        $request = new Request([], [], [], [], [], ['REQUEST_METHOD' => 'POST']);

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock);

        $this->assertSame($expectedResponse, $response);
    }

    public function testNouveauProduitAvecFormulaireInvalide(): void
    {
        $formStub = $this->createMock(FormInterface::class);
        $formStub->expects($this->once())
            ->method('handleRequest')
            ->with($this->isInstanceOf(Request::class));
        $formStub->method('isSubmitted')->willReturn(true);
        $formStub->method('isValid')->willReturn(false);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $controller = $this->getMockBuilder(ProductController::class)
            ->onlyMethods(['createForm', 'isGranted', 'render'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('createForm')
            ->with(ProductFormType::class, $this->isInstanceOf(Product::class), ['is_edit' => false])
            ->willReturn($formStub);

        $controller->method('isGranted')->willReturn(true);

        $expectedResponse = new Response();
        $controller->expects($this->once())
            ->method('render')
            ->with('product/new.html.twig', $this->callback(function (array $params) use ($formStub) {
                    return isset($params['form'], $params['isEdit'], $params['product'])
                        && $params['form'] === $formStub
                        && $params['isEdit'] === false
                        && $params['product'] instanceof Product;
                })
            )
            ->willReturn($expectedResponse);

        $request = new Request([], [], [], [], [], ['REQUEST_METHOD' => 'POST']);

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock);

        $this->assertSame($expectedResponse, $response);
    }

    public function testNouveauProduitAccesRefuse(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $controller = $this->getMockBuilder(ProductController::class)
            ->onlyMethods(['isGranted', 'addFlash', 'redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('isGranted')->willReturn(false);

        $controller->expects($this->once())
            ->method('addFlash')
            ->with('error', 'product.access_denied_new');

        $expectedResponse = new RedirectResponse('/product');
        $controller->expects($this->once())
            ->method('redirectToRoute')
            ->with('app_home')
            ->willReturn($expectedResponse);

        $request = new Request();

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock);

        $this->assertSame($expectedResponse, $response);
    }
}
