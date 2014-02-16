<?php
namespace FP\FEBundle\Controller\partial;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SponserPartialController extends Controller
{
    public function getSponsersAction(Request $request, $sponsers = null)
    {
        $heading = $sponsers !== null ? "Project sponsers" : "Our Sponsers";
        $sponsers = $sponsers !== null ? $sponsers : $this->getDoctrine()->getRepository("FPBEBundle:Sponser")->getGlobalSponsers();
        $content = $this->renderView("FPFEBundle:partial:sponsers.html.twig", array("sponsers" => $sponsers, "heading" => $heading));

        $response = new Response();
        $response->setPublic();
        $response->setContent($content);
        $response->setEtag(md5($content));
        $response->isNotModified($request);
        return $response;
    }
}