<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\Token\TokenGenerator;
use App\Utils\FakerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    use FakerTrait;

    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private TokenGenerator $tokenGenerator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = $this->getFakerFactory();
        for ($i = 0; $i < random_int(50, 100); $i++) {
            $user = new User();

            $user->setEmail($faker->email)
                ->setUsername($faker->userName)
                ->setPassword($this->hasher->hashPassword($user, 'pass'))
                ->setCreatedAt($this->setDateTimeBetween())
                ->setUpdatedAt($this->setDateTimeAfter($user->getCreatedAt()))
                ->setRoles($this->randomElements(['ROLE_ADMIN', 'ROLE_USER', 'ROLE_API'], 1));
            if(in_array('ROLE_API', $user->getRoles()) || in_array('ROLE_AADMIN', $user->getRoles())) {
                $user->setApiToken($this->tokenGenerator->generate(80));
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
