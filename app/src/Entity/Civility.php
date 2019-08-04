<?php

namespace App\Entity;

use App\Civility\CivilityRequest;
use App\Model\CivilityInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="civility")
 *
 * Define the properties of the Civility entity
 */
class Civility implements CivilityInterface
{
    /**
     * Civility id.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"Default","user_info"})
     */
    private $id;

    /**
     * Civility name.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     * @Groups({"Default","user_info"})
     */
    private $name;

    /**
     * Civility code.
     *
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10)
     * @Groups({"Default","user_info"})
     */
    private $code;

    public static function create(string $name, string $code): self
    {
        $civility = new self();

        $civility->name = $name;
        $civility->code = $code;

        return $civility;
    }

    public function update(CivilityRequest $civilityRequest): void
    {
        $this->name = $civilityRequest->name;
        $this->code = $civilityRequest->code;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
