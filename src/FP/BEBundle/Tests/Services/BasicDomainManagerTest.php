<?php
namespace FP\BEBundle\Tests\Services;

use FP\BEBundle\Entity\About;
use FP\BEBundle\Tests\ModelTestCase;

class BasicDomainManagerTest extends ModelTestCase
{
    public function testSuccess()
    {
        $about = new About();
        $about->getDescription("This is description");
        $client = static::createClient();

        $container = $client->getContainer();
        $manager = $container->get("fpbe.basic_domain_manager");

        $result = $manager->saveRecord($about);
        $this->assertEquals(gettype($about), gettype($result));
        $this->assertNotNull($result->getId());

        $doctrine = $container->get("doctrine");
        $db_about = $doctrine->getManager()->getRepository("FPBEBundle:About")->find($result->getId());
        $this->assertEquals(gettype($about), gettype($db_about));
        $db_about->setDescription("tete");
        $result = $manager->saveRecord($db_about);
        $updated_db_about = $doctrine->getManager()->getRepository("FPBEBundle:About")->find($result->getId());
        $this->assertEquals("tete", $updated_db_about->getDescription());

    }
}