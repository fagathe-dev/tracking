<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/xtrakSite', name: 'admin_site_')]
final class XtrakSiteController extends AbstractController
{

    public function __construct()
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render($this->getTemplate('index'));
    }

    /**
     * @param string $path
     * 
     * @return string
     */
    private function getTemplate(string $path): string
    {
        return 'admin/xtrakSite/' . $path . '.html.twig';
    }
}
