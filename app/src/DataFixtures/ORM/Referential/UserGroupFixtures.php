<?php

namespace App\DataFixtures\ORM\Referential;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\GroupManagerInterface;

class UserGroupFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var GroupManager
     */
    private $groupManager;

    public function __construct(GroupManagerInterface $groupManager)
    {
        $this->groupManager = $groupManager;
    }

    public function load(ObjectManager $manager)
    {
        $this->createGroups();
    }

    private function createGroups()
    {
        $groups = $this->getUserGroups();

        foreach ($groups as $group) {
            $newGroup = $this->groupManager->createGroup($group['name']);

            foreach ($group['role'] as $role) {
                $newGroup->addRole($role);
            }
            $newGroup->setSuperAdmin($group['superAdmin']);
            $newGroup->setDefaultGroup($group['defaultGroup']);
            $this->addReference($group['reference'], $newGroup);
            $this->groupManager->updateGroup($newGroup);
        }
    }

    private function getUserGroups()
    {
        return [
                [
                    'name' => 'Super administrateur',
                    'role' => [
                        'ROLE_CIVILITY_VIEW',
                        'ROLE_CIVILITY_EDIT',
                        'ROLE_CIVILITY_CREATE',
                        'ROLE_CIVILITY_DELETE',
                    ],
                    'reference'    => 'super-admin-group',
                    'superAdmin'   => true,
                    'defaultGroup' => false,
                ],
                [
                    'name' => 'User',
                    'role' => [
                        'ROLE_USER',
                    ],
                    'reference'    => 'user-group',
                    'superAdmin'   => false,
                    'defaultGroup' => true,
                ],
        ];
    }

    public static function getGroups(): array
    {
        return ['referential'];
    }
}
