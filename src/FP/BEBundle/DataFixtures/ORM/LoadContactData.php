<?php
namespace FP\FEBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\Persistence\ObjectManager;
use FP\BEBundle\Entity\Contact;

class LoadContactData extends AbstractFixture
{
    function load(ObjectManager $manager)
    {
        $contact = new Contact();
        $contact->setAddress("Address");
        $manager->persist($contact);
        $manager->flush();
    }
}