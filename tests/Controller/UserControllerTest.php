<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Controller\UserController;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends TestCase
{
    public function testNouvelUtilisateurAvecFormulaireValide(): void
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
            ->with($this->isInstanceOf(User::class));
        $entityManagerMock->expects($this->once())
            ->method('flush');
    
        $userPasswordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $userPasswordHasherMock->expects($this->once())
            ->method('hashPassword')
            ->with($this->isInstanceOf(User::class), 'plainPassword')
            ->willReturn('hashedPassword');
    
        $controller = $this->getMockBuilder(UserController::class)
            ->onlyMethods(['createForm', 'isGranted', 'addFlash', 'redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();
    
        $controller->method('createForm')
            ->willReturnCallback(function ($type, $user, $options) use ($formStub) {
                $user->setEmail('email@example.com')
                    ->setFirstname('firstname')
                    ->setLastname('lastname')
                    ->setRoles(['ROLE_USER'])
                    ->setPassword('plainPassword');
                return $formStub;
            });
    
        $controller->method('isGranted')->willReturn(true);
    
        $controller->expects($this->once())
            ->method('addFlash')
            ->with('success', 'user.created');
    
        $expectedResponse = new RedirectResponse('/user');
        $controller->method('redirectToRoute')
            ->with('app_user_index')
            ->willReturn($expectedResponse);
    
        $request = new Request([], [], [], [], [], ['REQUEST_METHOD' => 'POST']);
        
        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock, $userPasswordHasherMock);
    
        $this->assertSame($expectedResponse, $response);
    }    

    public function testNouvelUtilisateurAvecFormulaireInvalide(): void
    {
        $formStub = $this->createMock(FormInterface::class);
        $formStub->expects($this->once())
            ->method('handleRequest')
            ->with($this->isInstanceOf(Request::class));
        $formStub->method('isSubmitted')->willReturn(true);
        $formStub->method('isValid')->willReturn(false);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $userPasswordHasherMock = $this->createMock(UserPasswordHasherInterface::class);

        $controller = $this->getMockBuilder(UserController::class)
            ->onlyMethods(['createForm', 'isGranted', 'render'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('createForm')
            ->with(UserFormType::class, $this->isInstanceOf(User::class), ['is_edit' => false])
            ->willReturn($formStub);

        $controller->method('isGranted')->willReturn(true);

        $expectedResponse = new Response();
        $controller->expects($this->once())
            ->method('render')
            ->with('user/new.html.twig', $this->callback(function (array $params) use ($formStub) {
                    return isset($params['form'], $params['isEdit'], $params['user'])
                        && $params['form'] === $formStub
                        && $params['isEdit'] === false
                        && $params['user'] instanceof User;
                })
            )
            ->willReturn($expectedResponse);

        $request = new Request([], [], [], [], [], ['REQUEST_METHOD' => 'POST']);

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock, $userPasswordHasherMock);

        $this->assertSame($expectedResponse, $response);
    }

    public function testNouvelUtilisateurAccesRefuse(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $userPasswordHasherMock = $this->createMock(UserPasswordHasherInterface::class);

        $controller = $this->getMockBuilder(\App\Controller\UserController::class)
            ->onlyMethods(['isGranted', 'addFlash', 'redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->method('isGranted')->willReturn(false);

        $controller->expects($this->once())
            ->method('addFlash')
            ->with('error', 'user.access_denied_new');

        $expectedResponse = new RedirectResponse('/user');
        $controller->expects($this->once())
            ->method('redirectToRoute')
            ->with('app_home')
            ->willReturn($expectedResponse);

        $request = new Request();

        $response = call_user_func([$controller, 'new'], $request, $entityManagerMock, $userPasswordHasherMock);

        $this->assertSame($expectedResponse, $response);
    }
}
