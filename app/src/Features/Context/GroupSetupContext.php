<?php

namespace App\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use FOS\UserBundle\Model\GroupManagerInterface;

class GroupSetupContext implements Context, SnippetAcceptingContext
{
    /**
     * @var GroupManagerInterface
     */
    private $groupManager;

    public function __construct(GroupManagerInterface $groupManager)
    {
        $this->groupManager = $groupManager;
    }

    /**
     * @Given there are Groups with the following details:
     */
    public function thereAreGroupsWithTheFollowingDetails(TableNode $groups): void
    {
        foreach ($groups->getColumnsHash() as $key => $val) {
            $group = $this->groupManager->createGroup($val['name']);
            $roles = explode(',', $val['roles']);
            foreach ($roles as $role) {
                $group->addRole($role);
            }
            $group->setSuperAdmin($val['superAdmin']);
            $this->groupManager->updateGroup($group);
        }
    }
}
