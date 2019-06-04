<?php

namespace App\Model;

interface CivilityInterface
{
    public function getId(): ?int;

    public function setName(string $name): void;

    public function getName(): ?string;

    public function setCode(string $code): void;

    public function getCode(): ?string;
}
