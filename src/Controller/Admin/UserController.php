<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\App\Form\Admin\CreateUserType;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
final class UserController extends AbstractController
{

    public function __construct(
        private UserService $service
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
        $form = $this->createForm(CreateUserType::class, $user);

        return $this->render('admin/user/new.html.twig', compact('breadcrumb', 'user', 'form'));
    }
}
