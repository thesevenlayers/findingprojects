<?php

namespace FP\FEBundle\Controller;

use FP\BEBundle\Entity\Artist;
use FP\BEBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    protected $response = null;

    public function __construct()
    {
        $this->response = new Response();
        $this->response->setPublic();
        $this->response->setSharedMaxAge(86400);
        $this->response->headers->addCacheControlDirective("must-revalidate", true);
    }

    public function homeAction()
    {
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(86400);
        $response->headers->addCacheControlDirective("must-revalidate", true);
        $response->setContent($this->renderView("FPFEBundle:Default:homepage.html.twig"));
        return $response;
    }

    public function aboutAction()
    {
        $this->response->setContent($this->renderView("FPFEBundle:Default:about.html.twig"));
        return $this->response;
    }

    public function projectsAction()
    {
        $this->response->setContent($this->renderView("FPFEBundle:Default:projects_index.html.twig"));
        return $this->response;
    }

    public function projectDisplayAction(Project $project)
    {
        $this->response->setContent($this->renderView("FPFEBundle:Default:projects_inner.html.twig", array("project" => $project)));
        return $this->response;
    }

    public function artistsAction()
    {
        $this->response->setContent($this->renderView("FPFEBundle:Default:artists_index.html.twig"));
        return $this->response;
    }

    public function artistDisplayAction(Artist $artist)
    {
        $this->response->setContent($this->renderView("FPFEBundle:Default:artists_inner.html.twig", array("artist" => $artist)));
        return $this->response;
    }

    public function contactAction()
    {
        $this->response->setContent($this->renderView("FPFEBundle:Default:contact.html.twig"));
        return $this->response;
    }
}
