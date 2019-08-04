<?php

namespace App\Civility;

use App\Entity\Civility;
use Symfony\Component\Validator\Constraints as Assert;

class CivilityRequest
{
    /**
     * Civility name.
     *
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 255
     * )
     */
    public $name;

    /**
     * Civility code.
     *
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 10
     * )
     */
    public $code;

    public static function createFromCivility(Civility $civility): self
    {
        $dto = new self();
        $dto->name = $civility->getName();
        $dto->code = $civility->getCode();

        return $dto;
    }
}
