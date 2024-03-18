<?php

namespace App\Service\Xtrak;

use App\Entity\XtrakSite;
use App\Helpers\DateTimeHelperTrait;
use App\Repository\XtrakSiteRepository;
use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class XtrakSiteService
{

    use ServiceTrait;
    use DateTimeHelperTrait;

    public function __construct(
        private XtrakSiteRepository $repository,
        private PaginatorInterface $paginator,
        private EntityManagerInterface $manager,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }


    /**
     * @param  Request $request
     * @return array
     */
    public function getPagination(Request $request): array
    {

        $data = $this->repository->findAll();
        $page = $request->query->getInt('page', 1);
        $nbItems = $request->query->getInt('nbItems', 10);

        $pagination = $this->paginator->paginate(
            $data,
            /* query NOT result */
            $page,
            /*page number*/
            $nbItems, /*limit per page*/
        );

        $maxPage = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());

        if ($page > $maxPage) {
            $numberCurrentResults = $pagination->getTotalItemCount();
        } else {
            $numberCurrentResults = ($pagination->getCurrentPageNumber() - 1) * $pagination->getItemNumberPerPage() + count($pagination->getItems());
        }

        return compact('pagination', 'numberCurrentResults');
    }

    /**
     * @param Request $request
     * 
     * @return array
     */
    public function index(Request $request): array
    {
        return [
            'breadcrumb' => $this->breadcrumb(),
            ...$this->getPagination($request),
        ];
    }

    /**
     * create
     * @param XtrakSite $site
     * 
     * @return bool
     */
    public function create(XtrakSite $site): bool
    {
        $site->setCreatedAt($this->now());

        $result = $this->save($site);

        if ($result) {
            $this->addFlash('Site crée 🚀', 'success');
        } else {
            $this->addFlash('Une erreur est survenue lors de l\'enregistrement de ce site !', 'danger');
        }

        return $result;
    }

    /**
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function breadcrumb(array $items = []): Breadcrumb
    {
        return new Breadcrumb([
            new BreadcrumbItem('Liste des sites', $this->urlGenerator->generate('admin_xtrakSite_index')),
            ...$items
        ]);
    }


    /**
     * @param XtrakSite $site
     * 
     * @return bool
     */
    public function update(XtrakSite $site): bool
    {
        $site->setUpdatedAt($this->now());

        $result = $this->save($site);

        if ($result) {
            $this->addFlash('Utilisateur mis à jour 🚀', 'success');
        } else {
            $this->addFlash('Une erreur est survenue lors de la mise à jour de ce compte !', 'danger');
        }

        return $result;
    }

    /**
     * save
     *
     * @param  XtrakSite $site
     * @return bool
     */
    public function save(XtrakSite $site): bool
    {
        try {
            $this->manager->persist($site);
            $this->manager->flush();
            return true;
        } catch (ORMException $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return false;
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return false;
        }
    }
}
