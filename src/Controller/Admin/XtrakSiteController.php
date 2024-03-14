<?php

namespace App\Controller\Admin;

use App\Service\Xtrak\XtrakSiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/xtrakSite', name: 'admin_xtrakSite_')]
final class XtrakSiteController extends AbstractController
{

    public function __construct(
        private XtrakSiteService $service,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render($this->getTemplate('index'), $this->service->index($request));
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
