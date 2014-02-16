<?php
namespace FP\FEBundle\Controller\partial;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AboutPartialController extends Controller
{
    public function getAboutContentAction(Request $request)
    {
        $abouts = $this->getDoctrine()->getRepository("FPBEBundle:About")->findAll();
        $about = array_pop($abouts);
        $content = $this->renderView("FPFEBundle:partial:about_content.html.twig", array("about" => $about));

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