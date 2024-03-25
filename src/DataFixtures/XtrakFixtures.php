<?php

namespace App\DataFixtures;

use App\Entity\XtrakCode;
use App\Entity\XtrakSite;
use App\Enum\Xtrak\EnvEnum;
use App\Helpers\DateTimeHelperTrait;
use App\Utils\FakerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class XtrakFixtures extends Fixture
{

    use FakerTrait;
    use DateTimeHelperTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = $this->getFakerFactory();

        for ($i = 0; $i < random_int(10, 20); $i++) {
            $site = new XtrakSite();
            $site
                ->setName(strtoupper(join('_', $faker->words(2))))
                ->setCreatedAt($this->setDateTimeBetween('-6 months'))
                ->setUpdatedAt($this->setDateTimeAfter($site->getCreatedAt()))
                ->setIsActive($this->randomElement([true, false]));
            $domain = $faker->domainName();

            foreach (EnvEnum::cases() as $env) {
                $xtrakCode = new XtrakCode;

                $xtrakCode->setDomain(strtolower($env) . '.' . $domain)
                    ->setEnv($env)
                    ->setIsActive($xtrakCode->getEnv() !== EnvEnum::ENV_PROD)
                    ->setCreatedAt($this->setDateTimeAfter($site->getCreatedAt()))
                    ->setUpdatedAt($this->setDateTimeAfter($xtrakCode->getCreatedAt()))
                    ->setCode($site->getName() . '_' . $xtrakCode->getEnv());

                $site->addXtrakCode($xtrakCode);
            }

            $manager->persist($site);
        }

        $manager->flush();
    }
}
