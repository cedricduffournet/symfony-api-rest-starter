<?php

namespace App\Civility;

use App\Entity\Civility;

interface CivilityServiceInterface
{
    public function createCivility(): Civility;

    public function getCivility(int $civilityId): Civility;

    public function getAllCivilities(): array;

    public function updateCivility(Civility $civility): void;

    public function deleteCivility(Civility $civility): void;
}
