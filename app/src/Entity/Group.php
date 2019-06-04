<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * Group.
 *
 * @ORM\Table(name="fos_group")
 * @ORM\Entity
 * @ExclusionPolicy("none")
 */
class Group extends BaseGroup
{
    /**
     * Identifier of the group.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"user_info"})
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="groups",cascade={"remove", "persist"})
     * @Exclude
     */
    private $users;

    /**
     * Is group superadmin?
     *
     * @var bool
     *
     * @ORM\Column(name="super_admin", type="boolean")
     * @Groups({"user_info"})
     * @SerializedName("superAdmin")
     */
    private $superAdmin = false;

    /**
     * Is default group ?
     *
     * @var bool
     *
     * @ORM\Column(name="default_group", type="boolean")
     * @Groups({ "user_info"})
     * @SerializedName("defaultGroup")
     */
    private $defaultGroup = false;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct($name)
    {
        parent::__construct($name);
        $this->users = new ArrayCollection();
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get superAdmin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->superAdmin;
    }

    /**
     * Set setSuperAdmin.
     *
     * @param bool $superAdmin
     *
     * @return Group
     */
    public function setSuperAdmin($superAdmin)
    {
        $this->superAdmin = $superAdmin;

        return $this;
    }

    /**
     * Get defaultGroup.
     *
     * @return bool
     */
    public function isDefaultGroup()
    {
        return $this->superAdmin;
    }

    /**
     * Set setDefaultGroup.
     *
     * @param bool $defaultGroup
     *
     * @return Group
     */
    public function setDefaultGroup($defaultGroup)
    {
        $this->defaultGroup = $defaultGroup;

        return $this;
    }
}
