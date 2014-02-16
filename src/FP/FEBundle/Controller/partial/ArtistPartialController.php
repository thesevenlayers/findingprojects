<?php
namespace FP\FEBundle\Controller\partial;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtistPartialController extends Controller
{
    public function getArtistContentAction(Request $request)
    {
        $items = $this->getDoctrine()->getRepository("FPBEBundle:Artist")->findAll();
        $content = $this->renderView("FPFEBundle:partial:artist_content.html.twig", array("items" => $items));

        $response = new Response();
        $response->setEtag(md5($content));
        $response->isNotModified($request);
        $response->setContent($content);

        return $response;
    }
}