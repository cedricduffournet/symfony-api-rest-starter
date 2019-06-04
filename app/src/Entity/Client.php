<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * Client.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Client extends BaseClient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\Column(name="allowed_scopes", type="array",nullable=true)
     */
    protected $allowedScopes;

    public function __construct()
    {
        parent::__construct();
        $this->allowedScopes = [];
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Client
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function hasFrameColumns($frameColumn)
    {
        if (!is_array($this->frameColumns)) {
            return false;
        }

        return in_array($frameColumn, $this->frameColumns, true);
    }

    public function setAllowedScopes($allowedScopes = [])
    {
        $this->allowedScopes = $allowedScopes;

        return $this;
    }

    public function getAllowedScopes()
    {
        return $this->allowedScopes;
    }
}
