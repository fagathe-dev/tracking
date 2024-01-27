<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/login', name: 'auth_')]
class LoginController extends AbstractController
{

    #[Route('', name: 'login')]
    public function index(): Response
    {
        return $this->render('auth/login.html.twig');
    }
}
