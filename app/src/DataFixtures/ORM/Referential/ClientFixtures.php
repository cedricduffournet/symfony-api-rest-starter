<?php

namespace App\DataFixtures\ORM\Referential;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class ClientFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var ClientManagerInterface
     */
    private $clientManager;

    public function __construct(ClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    public function load(ObjectManager $manager)
    {
        $client = $this->clientManager->createClient();
        $client->setAllowedGrantTypes(['password', 'refresh_token']);
        $client->setId(1);
        $this->clientManager->updateClient($client);
        $output = new ConsoleOutput();

        $output->writeln(
            sprintf(
                'Added a new client with public id <info>%s</info>, secret <info>%s</info>',
                $client->getPublicId(),
                $client->getSecret()
            )
        );
    }

    public static function getGroups(): array
    {
        return ['referential'];
    }
}
