<?php

namespace App\DataFixtures\Referential;

use App\Civility\CivilityServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @var CivilityService
     */
    private $civilityService;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var GroupManagerInterface
     */
    private $groupManager;

    public function __construct(UserManagerInterface $userManager, CivilityServiceInterface $civilityService, GroupManagerInterface $groupService)
    {
        $this->userManager = $userManager;
        $this->civilityService = $civilityService;
        $this->groupManager = $groupService;
    }

    public function load(ObjectManager $manager)
    {
        $this->createUsers();
    }

    private function createUsers(): void
    {
        $users = $this->getUsers();

        foreach ($users as $userItem) {
            $user = $this->userManager->createUser();
            $user->setFirstname($userItem['firstname']);
            $user->setLastname($userItem['lastname']);
            $user->setTelephoneNumber($userItem['telephoneNumber']);
            $user->setEmail($userItem['email']);
            if (!isset($userItem['password'])) {
                $user->setPlainPassword($userItem['email'].'pwd');
            } else {
                $user->setPlainPassword($userItem['password']);
            }

            $user->setEnabled(1);
            $user->setWebmaster($userItem['webmaster']);
            $civility = $this->civilityService->getCivility($userItem['civilityId']);
            $user->setCivility($civility);

            foreach ($userItem['groups'] as $groupName) {
                $group = $this->groupManager->findGroupByName($groupName);
                $user->addGroup($group);
            }
            $this->userManager->updateUser($user);
        }
    }

    private function getUsers(): array
    {
        return [
                [
                    'firstname'       => 'Hyperview',
                    'lastname'        => getenv('APP_PROJECT_NAME'),
                    'telephoneNumber' => '01.01.01.01.01',
                    'email'           => getenv('APP_PROJECT_MAIL'),
                    'civilityId'      => 1,
                    'webmaster'       => true,
                    'reference'       => 'user-fixture',
                    'groups'          => [],
                ],
                [
                    'firstname'       => 'Super',
                    'lastname'        => 'Admin',
                    'telephoneNumber' => '02.02.02.02.02',
                    'email'           => 'superadmin@dev.com',
                    'civilityId'      => 1,
                    'password'        => 'superadminpwd',
                    'webmaster'       => false,
                    'groups'          => [
                        'Super administrateur',
                    ],
                ],
        ];
    }

    public static function getGroups(): array
    {
        return ['referential'];
    }

    public function getDependencies(): array
    {
        return [
            CivilityFixtures::class,
            UserGroupFixtures::class,
        ];
    }
}
