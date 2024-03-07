<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\App\Form\Admin\UserType;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Service\Token\TokenGenerator;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
final class UserController extends AbstractController
{

    public function __construct(
        private UserService $service,
        private TokenGenerator $tokenGenerator
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('admin/user/index.html.twig', $this->service->index($request));
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $breadcrumb = $this->service->breadcrumb([
            new BreadcrumbItem('Nouveau'),
        ]);
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->tokenGenerator->generate(15);
            $user->setPassword($password);
            if ($this->service->create($user)) {
                return $this->redirectToRoute('admin_user_index');
            }
        }

        return $this->render('admin/user/new.html.twig', compact('breadcrumb', 'user', 'form'));
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request): Response
    {
        $breadcrumb = $this->service->breadcrumb([
            new BreadcrumbItem('Modifier #' . $user->getId()),
        ]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->update($user);
        }

        return $this->render('admin/user/new.html.twig', compact('breadcrumb', 'user', 'form'));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(User $user, Request $request): Response
    {
        $response = $this->service->remove($user);
        
        if ($response === false) {
            return $this->json(
                'BAD REQUEST',
                Response::HTTP_BAD_REQUEST
            );
        }
        
        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
        );
    }
}
