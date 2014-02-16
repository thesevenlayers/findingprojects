<?php
namespace FP\FEBundle\Controller\partial;

use FP\BEBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectsPartialController extends Controller
{
    public function getProjectsContentAction(Request $request)
    {
        $items = $this->getDoctrine()->getRepository("FPBEBundle:Project")->findAll();
        $video_image_urls = array();
        foreach($items as $item)
        {
            if($item->getVideo() !== null)
            {
                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$item->getVideo().".php"));
                $video_image_urls[$item->getId()] = $hash[0]["thumbnail_large"];
            }
        }
        $content = $this->renderView("@FPFE/partial/projects_content.html.twig", array(
                "items" => $items,
                "video_image_urls" => $video_image_urls,
            ));

        $response = new Response();
        $response->setEtag(md5($content));
        $response->isNotModified($request);
        $response->setContent($content);

        return $response;
    }

    public function getProjectInnerContentAction(Request $request, Project $project)
    {
//        $project->setFileDir($project->getId());
        $content = $this->renderView("@FPFE/partial/project_inner_content.html.twig", array(
                "item" => $project,
            ));

        $response = new Response();
        $response->setEtag(md5($content));
        $response->isNotModified($request);
        $response->setContent($content);

        return $response;
    }
}