<?php

namespace App\Features\Context;

use App\Civility\CivilityServiceInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;

class CivilitySetupContext implements Context, SnippetAcceptingContext
{
    /**
     * @var CivilityService
     */
    private $civilityService;

    public function __construct(CivilityServiceInterface $civilityService)
    {
        $this->civilityService = $civilityService;
    }

    /**
     * @Given there are Civilities with the following details:
     */
    public function thereAreCivilitiesWithTheFollowingDetails(TableNode $civilities): void
    {
        foreach ($civilities->getColumnsHash() as $key => $val) {
            $civility = $this->civilityService->createCivility();
            $civility->setCode($val['code']);
            $civility->setName($val['name']);
            $this->civilityService->updateCivility($civility);
        }
    }
}
