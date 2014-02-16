<?php
namespace FP\FEBundle\Controller\partial;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomePartialController extends Controller
{
    public function getHomeContentAction(Request $request)
    {
        $items = $this->getDoctrine()->getRepository("FPBEBundle:HomeItem")->findAll();
        $content = $this->renderView("@FPFE/partial/home_content.html.twig", array(
                "items" => $items,
            ));

        $response = new Response();
        $response->setEtag(md5($content));
        $response->isNotModified($request);
        $response->setContent($content);

        return $response;
    }
}