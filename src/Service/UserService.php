<?php

namespace App\Service;

use App\Entity\User;
use App\Helpers\DateTimeHelperTrait;
use App\Repository\UserRepository;
use App\Utils\ServiceTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService
{

    use ServiceTrait;
    use DateTimeHelperTrait;

    public function __construct(
        private UserRepository $repository,
        // private PaginatorInterface $paginator,
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $manager,
        private Security $security
    ) {
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
            $this->addFlash('danger', $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
            return false;
        }
    }

    /**
     * hash
     *
     * @param  mixed $user
     * @return User
     */
    private function hash(User $user): User
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
            $this->addFlash('success', 'Utilisateur crée 🚀');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement de ce compte !');
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
            $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de votre compte !');
            return false;
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
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
