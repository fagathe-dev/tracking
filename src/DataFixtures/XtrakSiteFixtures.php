<?php

namespace App\DataFixtures;

use App\Entity\XtrakSite;
use App\Enum\Xtrak\EnvEnum;
use App\Utils\FakerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;;

class XtrakSiteFixtures extends Fixture
{

    use FakerTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = $this->getFakerFactory();

        for ($i = 0; $i < random_int(10, 20); $i++) {
            $site = new XtrakSite();
            $site
                ->setName(strtoupper(join('_', $faker->words(2))))
                ->setDomain($faker->domainName())
                ->setEnv($this->randomElement(EnvEnum::cases()))
                ->setCreatedAt($this->setDateTimeBetween('-6 months'))
                ->setUpdatedAt($this->setDateTimeAfter($site->getCreatedAt()))
                ->setIsActive($this->randomElement([true, false]));

            $manager->persist($site);
        }

        $manager->flush();
    }
}