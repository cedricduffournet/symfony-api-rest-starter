<?php

namespace App\Service;

use App\Entity\Civility;
use App\Repository\CivilityRepositoryInterface;

class CivilityService implements CivilityServiceInterface
{
    /**
     * @var CivilityRepository
     */
    private $civilityRepository;

    public function __construct(CivilityRepositoryInterface $civilityRepository)
    {
        $this->civilityRepository = $civilityRepository;
    }

    public function createCivility(): Civility
    {
        return new Civility();
    }

    public function getCivility(int $civilityId): Civility
    {
        return $this->civilityRepository->find($civilityId);
    }

    public function getAllCivilities(): array
    {
        return $this->civilityRepository->findAll();
    }

    public function updateCivility(Civility $civility): void
    {
        $this->civilityRepository->save($civility);
    }

    public function deleteCivility(Civility $civility): void
    {
        $this->civilityRepository->remove($civility);
    }
}
