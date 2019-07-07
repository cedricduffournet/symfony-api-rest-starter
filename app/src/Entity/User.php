<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 *
 * Define the properties of the User entity
 */
class User extends BaseUser
{
    /**
     * Identifier of the user.
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
     * Collection of group.
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="fos_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @Groups({"user_info"})
     */
    protected $groups;

    /**
     * User first name.
     *
     * @var string
     *
     * @ORM\Column(name="firstname", type="string")
     * @Groups({"user_info"})
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * User last name.
     *
     * @var string
     *
     * @ORM\Column(name="lastname", type="string")
     * @Groups({"user_info"})
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * User telephone number.
     *
     * @var string
     *
     * @ORM\Column(name="telephone_number", type="string", nullable=true, length=45)
     * @SerializedName("telephoneNumber")
     * @Groups({"user_info"})
     */
    protected $telephoneNumber;

    /**
     * User civility.
     *
     * @var Civility
     *
     * @ORM\ManyToOne(targetEntity="Civility")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_info"})
     * @Assert\NotBlank()
     */
    private $civility;

    /**
     * Is user the webmaster?
     *
     * @var string
     *
     * @ORM\Column(name="webmaster", type="boolean")
     * @SerializedName("webmaster")
     */
    protected $webmaster = false;

    /**
     * Url confirmation for email registration.
     *
     * @var string
     *
     * @ORM\Column(name="confirmation_url", type="string", nullable=true, length=255)
     * @Exclude
     */
    public $confirmationUrl = '';

    public function setGroups(Collection $groups = null): void
    {
        $this->groups = $groups;
    }

    public function hasGroup($name = ''): bool
    {
        return in_array($name, $this->getGroupNames());
    }

    public function removeAllGroups(): void
    {
        if ($this->groups) {
            $this->groups->clear();
        }
    }

    public function isSuperAdmin(): bool
    {
        $collection = $this->getGroups();

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('superAdmin', true));

        return $collection->matching($criteria)->count() > 0;
    }

    public function isAdmin(): bool
    {
        $collection = $this->getGroups();

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('admin', true));

        return $collection->matching($criteria)->count() > 0;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setTelephoneNumber(string $telephoneNumber): void
    {
        $this->telephoneNumber = $telephoneNumber;
    }

    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    public function setCivility(Civility $civility): void
    {
        $this->civility = $civility;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function setWebmaster(bool $webmaster): void
    {
        $this->webmaster = $webmaster;
    }

    public function isWebmaster(): bool
    {
        return $this->webmaster;
    }

    public function setEmail($email): void
    {
        parent::setEmail($email);
        parent::setUsername($email);
    }

    public function setConfirmationUrl($url): void
    {
        $this->confirmationUrl = $url;
    }

    public function getConfirmationUrl(): ?string
    {
        return $this->confirmationUrl;
    }
}
