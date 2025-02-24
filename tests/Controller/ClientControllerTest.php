<?php

namespace App\Tests\Controller;

use App\Entity\Client;
use App\Controller\ClientController;
use App\Form\ClientFormType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ClientControllerTest extends TestCase
{
    public function testNouveauClientAvecFormulaireValide(): void
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
            ->with($this->isInstanceOf(Client::class));
        $entityManagerMock->expects($this->once())
            ->method('flush');

        $controller = $this->getMockBuilder(ClientController::class)
            ->onlyMethods(['createForm', 'isGranted', 'addFlash', 'redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('createForm')
            ->willReturnCallback(function ($type, $client, $options) use ($formStub) {
                $client->setFirstname('firstname')
                    ->setLastname('name')
                    ->setEmail('email@example.com')
                    ->setPhoneNumber('+1234567890')
                    ->setAddress('123 Adress');
                return $formStub;
            });

        $controller->method('isGranted')->willReturn(true);

        $controller->expects($this->once())
            ->method('addFlash')
            ->with('success', 'client.created');

        $expectedResponse = new RedirectResponse('/client');
        $controller->method('redirectToRoute')
            ->with('app_client_index')
            ->willReturn($expectedResponse);

        $request = new Request([], [], [], [], [], ['REQUEST_METHOD' => 'POST']);

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock);

        $this->assertSame($expectedResponse, $response);
    }

    public function testNouveauClientAvecFormulaireInvalide(): void
    {
        $formStub = $this->createMock(FormInterface::class);
        $formStub->expects($this->once())
            ->method('handleRequest')
            ->with($this->isInstanceOf(Request::class));
        $formStub->method('isSubmitted')->willReturn(true);
        $formStub->method('isValid')->willReturn(false);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $controller = $this->getMockBuilder(ClientController::class)
            ->onlyMethods(['createForm', 'isGranted', 'render'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('createForm')
            ->with(ClientFormType::class, $this->isInstanceOf(Client::class), ['is_edit' => false])
            ->willReturn($formStub);

        $controller->method('isGranted')->willReturn(true);

        $expectedResponse = new Response();
        $controller->expects($this->once())
            ->method('render')
            ->with('client/new.html.twig', $this->callback(function (array $params) use ($formStub) {
                    return isset($params['form'], $params['isEdit'], $params['client'])
                        && $params['form'] === $formStub
                        && $params['isEdit'] === false
                        && $params['client'] instanceof Client;
                })
            )
            ->willReturn($expectedResponse);

        $request = new Request([], [], [], [], [], ['REQUEST_METHOD' => 'POST']);

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock);

        $this->assertSame($expectedResponse, $response);
    }

    public function testNouveauClientAccesRefuse(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $controller = $this->getMockBuilder(ClientController::class)
            ->onlyMethods(['isGranted', 'addFlash', 'redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('isGranted')->willReturn(false);

        $controller->expects($this->once())
            ->method('addFlash')
            ->with('error', 'client.access_denied_new');

        $expectedResponse = new RedirectResponse('/client');
        $controller->expects($this->once())
            ->method('redirectToRoute')
            ->with('app_home')
            ->willReturn($expectedResponse);

        $request = new Request();

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock);

        $this->assertSame($expectedResponse, $response);
    }
}
