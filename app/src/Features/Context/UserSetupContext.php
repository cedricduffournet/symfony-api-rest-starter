<?php

namespace App\Features\Context;

use App\Service\CivilityServiceInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Defines application features from the specific context.
 */
class UserSetupContext implements Context, SnippetAcceptingContext
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var GroupManager
     */
    private $groupManager;

    /**
     * @var CivilityService
     */
    private $civilityService;

    public function __construct(UserManagerInterface $userManager, GroupManagerInterface $groupManager, CivilityServiceInterface $civilityService)
    {
        $this->userManager = $userManager;
        $this->groupManager = $groupManager;
        $this->civilityService = $civilityService;
    }

    /**
     * @Given there are Users with the following details:
     */
    public function thereAreUsersWithTheFollowingDetails(TableNode $users): void
    {
        foreach ($users->getColumnsHash() as $key => $val) {
            $user = $this->userManager->createUser();
            $user->setFirstname($val['firstname']);
            $user->setLastname($val['lastname']);
            $user->setEmail($val['email']);
            $user->setPlainPassword($val['password']);
            $user->setEnabled(1);

            if (isset($val['telephoneNumber'])) {
                $user->setTelephoneNumber($val['telephoneNumber']);
            }

            if (isset($val['webmaster'])) {
                $user->setWebmaster($val['webmaster']);
            }

            $civility = $this->civilityService->getCivility($val['civilityId']);
            $user->setCivility($civility);
            $groups = explode(',', $val['groups']);
            foreach ($groups as $groupName) {
                $group = $this->groupManager->findGroupByName($groupName);
                $user->addGroup($group);
            }
            $this->userManager->updateUser($user);
        }
    }
}
