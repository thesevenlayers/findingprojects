<?php
namespace FP\FEBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\Persistence\ObjectManager;
use FP\BEBundle\Entity\Newsletter;

class LoadNewsletterData extends AbstractFixture
{
    function load(ObjectManager $manager)
    {
        $newsletter = new Newsletter();
        $newsletter->setDescription("Newsletter Description");
        $manager->persist($newsletter);
        $manager->flush();
    }
}