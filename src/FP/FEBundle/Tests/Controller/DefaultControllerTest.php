<?php

namespace FP\FEBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testRedirectHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $request = $client->getRequest();

        $this->assertEquals("Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction",$request->attributes->get("_controller"));
    }

    public function testHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        $request = $client->getRequest();

        $this->assertEquals("FP\FEBundle\Controller\DefaultController::homeAction",$request->attributes->get("_controller"));
        $this->assertEquals("Home", $crawler->filter("ul.menu > li:first-child > a")->text());
//        $this->assertEquals("Home", $crawler->filter("ul.menu > .current-menu-parent > a")->text());
    }
}
