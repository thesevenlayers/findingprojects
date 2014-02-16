<?php
namespace FP\BEBundle\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModelTestCase extends WebTestCase
{
    protected $em;
    protected $application;

    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->em);
        $mdf = $this->em->getMetadataFactory();
        $classes = $mdf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
//
//        $loader = new \Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader($client->getContainer());
//        $loader->loadFromDirectory(static::$kernel->locateResource("@FPFEBundle/DataFixtures/ORM"));
//        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
//        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
//        $executor->execute($loader->getFixtures());
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}