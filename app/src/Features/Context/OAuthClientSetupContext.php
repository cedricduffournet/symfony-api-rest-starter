<?php

namespace App\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;

class OAuthClientSetupContext implements Context, SnippetAcceptingContext
{
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;

    public function __construct(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * @BeforeScenario
     */
    public function loadOAuthClient(): void
    {
        $client = $this->clientManager->createClient();
        $client->setAllowedGrantTypes(['password']);

        $randomId = getenv('AUTH_CLIENT_ID');
        $explRandomId = explode('_', $randomId);
        $randomId = $explRandomId[1];
        $client->setRandomId($randomId);
        $client->setSecret(getenv('AUTH_CLIENT_SECRET'));

        $this->clientManager->updateClient($client);
    }
}
