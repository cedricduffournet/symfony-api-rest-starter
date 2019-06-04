<?php

namespace App\Repository;

use App\Entity\Civility;
use Doctrine\ORM\EntityManagerInterface;

/**
 * CivilityRepository.
 */
class CivilityRepository implements CivilityRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Civility::class);
    }

    public function find(int $id): Civility
    {
        return $this->objectRepository->find($id);
    }

    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }

    public function remove(Civility $civility): void
    {
        $this->entityManager->remove($civility);
        $this->entityManager->flush();
    }

    public function save(Civility $Civility): void
    {
        $this->entityManager->persist($Civility);
        $this->entityManager->flush();
    }
}
