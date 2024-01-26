<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('', name: 'app_default')]
final class DefaultController extends AbstractController
{

    public function __construct()
    {
    }

    #[Route('', name: '', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
