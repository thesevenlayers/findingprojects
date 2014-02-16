<?php
namespace FP\FEBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\Persistence\ObjectManager;
use FP\BEBundle\Entity\About;

class LoadAboutData extends AbstractFixture
{
    function load(ObjectManager $manager)
    {
        $about = new About();
        $about->setDescription("About Description");
        $manager->persist($about);
        $manager->flush();
    }
}