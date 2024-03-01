<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Utils\FakerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    use FakerTrait;

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = $this->getFakerFactory();
        for ($i=0; $i < 5; $i++) { 
            $user = new User();
            
            $user->setEmail($faker->email)
                ->setUsername($faker->userName)
                ->setPassword($this->hasher->hashPassword($user, 'pass'))
                ->setCreatedAt($this->setDateTimeBetween())
                ->setUpdatedAt($this->setDateTimeAfter($user->getCreatedAt()))
                ->setRoles(['ROLE_ADMIN'])
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
