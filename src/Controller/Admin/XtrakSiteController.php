<?php

namespace App\Controller\Admin;

use App\Entity\XtrakSite;
use App\Form\Admin\Xtrak\SiteType;
use App\Service\Breadcrumb\BreadcrumbItem;
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

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $breadcrumb = $this->service->breadcrumb([new BreadcrumbItem('Nouveau site')]);
        $site = new XtrakSite;
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->service->create($site)) {
                return $this->redirectToRoute('admin_xtrakSite_edit', [
                    'id' => $site->getId(),
                ]);
            }
        }

        return $this->render($this->getTemplate('new'), compact('form', 'breadcrumb'));
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, XtrakSite $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        return $this->render($this->getTemplate('edit'), compact('form'));
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
