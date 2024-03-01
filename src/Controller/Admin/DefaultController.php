<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
final class DefaultController extends AbstractController
{

    #[Route('', name: 'default', methods: ['GET'])]
    public function default(): Response
    {
        return $this->render('admin/default.html.twig');
    }
}
