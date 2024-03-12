<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\User\EditPasswordType;
use App\Form\Admin\User\UserType;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Service\Token\TokenGenerator;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $activeForm = $request->query->get('form');
        $nav = [
            ['label' => 'Informations', 'id' => 'infos', 'isActive' => $activeForm === null || $activeForm === 'infos', 'template' => 'infos.html.twig'],
            ['label' => 'Mot de passe', 'id' => 'password', 'isActive' => $activeForm && $activeForm === 'password', 'template' => 'password.html.twig'],
            ['label' => 'Paramètres', 'id' => 'settings', 'isActive' => $activeForm && $activeForm === 'settings', 'template' => 'settings.html.twig'],
        ];

        $breadcrumb = $this->service->breadcrumb([
            new BreadcrumbItem('Modifier #' . $user->getId()),
        ]);

        // Modifier le mot de passe de l'utilisateur
        $formPassword = $this->createForm(EditPasswordType::class);
        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $this->service->hash($user->setPassword($formPassword->get('password')->getData()));
            $this->service->update($user);
        }

        // Modifier les infos de l'utilisateur
        $formInfos = $this->createForm(UserType::class, $user);
        $formInfos->handleRequest($request);

        if ($formInfos->isSubmitted() && $formInfos->isValid()) {
            $this->service->update($user);
        }

        return $this->render('admin/user/edit.html.twig', compact('breadcrumb', 'user', 'formInfos', 'formPassword', 'nav'));
    }

    #[Route('/{id}/updateApiToken', name: 'updateApiToken', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function updateApiToken(User $user): JsonResponse
    {
        $response = $this->service->updateApiToken($user);

        if (is_bool($response)) {
            return $this->json(
                'BAD REQUEST',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json($response->data, $response->status, $response->headers);
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
