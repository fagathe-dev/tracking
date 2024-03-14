<?php

namespace App\Service;

use App\Entity\User;
use App\Helpers\DateTimeHelperTrait;
use App\Repository\UserRepository;
use App\Service\Breadcrumb\Breadcrumb;
use App\Service\Breadcrumb\BreadcrumbItem;
use App\Service\Token\TokenGenerator;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UserService
{

    use ServiceTrait;
    use DateTimeHelperTrait;

    public function __construct(
        private UserRepository $repository,
        private PaginatorInterface $paginator,
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $manager,
        private Security $security,
        private UrlGeneratorInterface $urlGenerator,
        private TokenGenerator $tokenGenerator,
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
        }  else {
            $numberCurrentResults = ($pagination->getCurrentPageNumber() - 1) * $pagination->getItemNumberPerPage() + count($pagination->getItems());
        }

        return compact('pagination', 'numberCurrentResults');
    }

    /**
     * index
     *
     * @param  mixed $request
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
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function breadcrumb(array $items = []): Breadcrumb
    {
        return new Breadcrumb([
            new BreadcrumbItem('Liste des utilisateurs', $this->urlGenerator->generate('admin_user_index')),
            ...$items
        ]);
    }

    /**
     * save
     *
     * @param  User $user
     * @return bool
     */
    public function save(User $user): bool
    {
        try {
            $this->manager->persist($user);
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

    /**
     * @param User $user
     * 
     * @return bool
     */
    public function update(User $user): bool
    {
        $user->setUpdatedAt($this->now());

        $result = $this->save($user);

        if ($result) {
            $this->addFlash('Utilisateur mis à jour 🚀', 'success');
        } else {
            $this->addFlash('Une erreur est survenue lors de la mise à jour de ce compte !', 'danger');
        }

        return $result;
    }

    /**
     * hash
     *
     * @param  mixed $user
     * @return User
     */
    public function hash(User $user): User
    {
        return $user->setPassword(
            $this->hasher->hashPassword($user, $user->getPassword())
        );
    }

    /**
     * create
     * @param User $user
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        $user->setCreatedAt($this->now());
        $this->hash($user);

        $result = $this->save($user);

        if ($result) {
            $this->addFlash('Utilisateur crée 🚀', 'success');
        } else {
            $this->addFlash('Une erreur est survenue lors de l\'enregistrement de ce compte !', 'danger');
        }

        return $result;
    }

    /**
     * @param User $user
     * 
     * @return bool
     */
    public function updateApiToken(User $user): bool|object
    {
        $token = $this->tokenGenerator->generate(80);

        $user->setApiToken($token);

        $result = $this->update($user);

        if ($result) {
            return $this->sendJson(compact('token'));
        }

        return $result;
    }

    /**
     * remove
     *
     * @param  User $object
     * @return object|bool
     */
    public function remove(User $user): bool|object
    {
        try {
            $this->manager->remove($user);
            $this->manager->flush();
            return $this->sendNoContent();
        } catch (ORMException $e) {
            $this->addFlash('Une erreur est survenue lors de la suppression de votre compte !', 'danger');
            return false;
        } catch (Exception $e) {
            $this->addFlash($e->getMessage(), 'danger');
            return false;
        }
    }

    /**
     * get logged User
     *
     * @return User
     */
    private function getUser(): ?User
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $user;
        }
        return null;
    }
}
