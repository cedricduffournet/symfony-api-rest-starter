<?php

namespace App\DataFixtures\Referential;

use App\Service\CivilityServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CivilityFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var CivilityService
     */
    private $civilityService;

    public function __construct(CivilityServiceInterface $civilityService)
    {
        $this->civilityService = $civilityService;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadCivilities();
    }

    private function loadCivilities(): void
    {
        $civilities = $this->getCivilities();

        foreach ($civilities as $value) {
            $civility = $this->civilityService->createCivility();
            $civility->setName($value['name']);
            $civility->setCode($value['code']);
            $this->civilityService->updateCivility($civility);
            $this->addReference('civility-'.$value['reference'], $civility);
        }
    }

    private function getCivilities(): array
    {
        return [
                ['reference' => 1, 'name' => 'Monsieur', 'code' => 'Mr'],
                ['reference' => 2, 'name' => 'Madame', 'code' => 'Mme'],
        ];
    }

    public static function getGroups(): array
    {
        return ['referential'];
    }
}
