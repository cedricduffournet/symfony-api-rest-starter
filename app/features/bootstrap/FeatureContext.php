<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $doctrine;
    private $manager;
    private $schemaTool;
    private $classes;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->manager = $doctrine->getManager();

        $this->schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->manager);

        $this->classes = $this->manager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @BeforeScenario
     */
    public function createSchema()
    {
        echo '-- DROP SCHEMA -- '."\n\n\n";

        $this->schemaTool->dropSchema($this->classes);

        echo '-- CREATE SCHEMA -- '."\n\n\n";
        $this->schemaTool->createSchema($this->classes);
    }
}
