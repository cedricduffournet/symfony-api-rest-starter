<?php

namespace App\Repository;

use App\Entity\Civility;

interface CivilityRepositoryInterface
{
    public function find(int $civilityId): Civility;

    public function findAll(): array;

    public function remove(Civility $civility): void;

    public function save(Civility $civility): void;
}
