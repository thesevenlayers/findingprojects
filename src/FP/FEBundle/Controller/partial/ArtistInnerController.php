<?php
namespace FP\FEBundle\Controller\partial;

use FP\BEBundle\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtistInnerController extends Controller
{
    public function getArtistInnerContentAction(Request $request, $id)
    {
        $artist = $this->getDoctrine()->getRepository("FPBEBundle:Artist")->find($id);
        if(!$artist)
        {
            return $this->createNotFoundException("Artist not found!");
        }

        $content = $this->renderView("@FPFE/partial/artist_inner_content.html.twig", array("artist" => $artist));

        $response = new Response();
        $response->setEtag(md5($content));
        $response->isNotModified($request);
        $response->setContent($content);

        return $response;

    }
}