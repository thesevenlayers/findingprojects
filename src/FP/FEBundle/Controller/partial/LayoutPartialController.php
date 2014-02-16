<?php
namespace FP\FEBundle\Controller\partial;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LayoutPartialController extends Controller
{
    public function getUrlsAction(Request $request)
    {
        $contacts = $this->getDoctrine()->getRepository("FPBEBundle:Contact")->findAll();
        $contact = array_pop($contacts);
        $content = $this->renderView("FPFEBundle:partial:urls_content.html.twig", array("contact" => $contact));

        $response = new Response();
        $response->setPublic();
        $response->setContent($content);
        $response->setEtag(md5($content));
        $response->isNotModified($request);
//        var_dump($content);
//        die;
        return $response;
    }
}