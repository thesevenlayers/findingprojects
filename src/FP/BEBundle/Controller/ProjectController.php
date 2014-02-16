<?php
namespace FP\BEBundle\Controller;

use FP\BEBundle\Entity\Artist;
use FP\BEBundle\Entity\Project;
use FP\BEBundle\Entity\Sponser;
use FP\BEBundle\Entity\ProjectFile;
use FP\BEBundle\Entity\ProjectPhoto;
use FP\BEBundle\Form\ProjectType;
use FP\BEBundle\Form\ProjectVideoType;
use FP\BEBundle\Form\SponserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProjectController extends Controller
{
    public function getListAction(Request $request)
    {
        $projects = $this->get("fpbe.basic_form_handler")->handleSearchForm($request, "project");
        return $this->render("FPBEBundle:Default/Project:project.html.twig", array("projects" => $projects));
    }

    public function getPartialProjectListAction($projects = null)
    {
        $items = $projects === null ? $this->getDoctrine()->getRepository("FPBEBundle:Project")->findAll() : $projects;
        $video_image_urls = array();
        foreach($items as $item)
        {
            if($item->getVideo() !== null)
            {
                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$item->getVideo().".php"));
                $video_image_urls[$item->getId()] = $hash[0]["thumbnail_medium"];
            }
        }
        $delete_form = $this->get("fpbe.basic_form_handler")->createDeleteForm();

        return $this->render("FPBEBundle:Default/Project/partial:project_list.html.twig", array(
                "items" => $items,
                "delete_form" => $delete_form->createView(),
                "video_image_urls" => $video_image_urls,
            ));
    }

    public function getNewAction()
    {
        $form = $this->createForm(new ProjectType(), new Project());
        $artists = $this->getDoctrine()->getRepository("FPBEBundle:Artist")->findAll();
        $artist_arr = array();
        foreach($artists as $artist)
        {
            $artist_arr[] = $artist->getArtistName();
        }
        return $this->render("FPBEBundle:Default/Project:project_new.html.twig", array(
                "form" => $form->createView(),
                "artists" => $this->get("serializer")->serialize($artist_arr, "json")
            ));
    }


    public function postNewAction(Request $request)
    {
        $artists = $request->query->has("artists") ? $request->query->get("artists") : null;

        $form = $this->createForm(new ProjectType(), new Project());

        $form->handleRequest($request);

        $project = $form->getData();

        $em = $this->getDoctrine()->getManager();

        if($artists != null)
        {
            foreach($artists as $artist_name)
            {
                $artist = $this->getDoctrine()->getRepository("FPBEBundle:Artist")->findByOneArtistName($artist_name);
                if(!$artist)
                {
                    $artist = new Artist();
                    $artist->setArtistName($artist_name);
                    $artist->setPutLink(false);

                }
                $artist->addProject($project);
                $project->addArtist($artist);
                $em->persist($artist);
            }
        }

        $em->persist($project);
        $em->flush();

        if($project)
        {
            return $this->redirect($this->generateUrl("fp.be.project.get_edit", array("id" => $project->getId())));
        }
        else
        {
            return $this->render("FPBEBundle:Default/Project:project_new.html.twig", array(
                    "form" => $form->createView(),
                ));
        }
    }

    public function getEditAction(Project $item)
    {
        $artist_arr = array();
        foreach($item->getArtists() as $artist)
        {
            $artist_arr[] = $artist->getArtistName();
        }

        $artists = $this->getDoctrine()->getRepository("FPBEBundle:Artist")->findAll();
        $artist_all = array();
        foreach($artists as $artist)
        {
            $artist_all[] = $artist->getArtistName();
        }

        return $this->render("FPBEBundle:Default/Project:project_edit.html.twig", array(
                "item" => $item,
                "artists" => $this->get("serializer")->serialize($artist_arr, "json"),
                "artists_all" => $this->get("serializer")->serialize($artist_all, "json")
            ));
    }

    public function getPartialEditFormAction($item)
    {
        $form = $this->get("fpbe.basic_form_handler")->createForm(new ProjectType(), $item);
        $form_sponser = $this->createForm(new SponserType(), new Sponser());
        $form_vid = $this->get("fpbe.basic_form_handler")->createForm(new ProjectVideoType(), $item);
        return $this->render("FPBEBundle:Default/Project/partial:project_edit_form.html.twig", array(
                "form" => $form->createView(),
                "form_sponser" => $form_sponser->createView(),
                "form_vid" => $form_vid->createView(),
                "item" => $item,
            ));
    }

    public function postEditAction(Request $request, Project $item)
    {
        $form = $this->createForm(new ProjectType(), $item);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $new_artists = $request->query->get("artists");
        $files = $request->files->get("files");

        if($new_artists)
        {
            $old_artists = array();
            foreach($item->getArtists() as $old_artist)
            {
                $old_artists[] = $old_artist->getArtistName();
            }

            $artist_remove = array_diff($old_artists, $new_artists);
            foreach($artist_remove as $old_artist)
            {
                $artist = $this->getDoctrine()->getRepository("FPBEBundle:Artist")->findByOneArtistName($old_artist);
                $item->removeArtist($artist);
                $artist->removeProject($item);
                $em->persist($item);
            }

            $artists_add = array_diff($new_artists, $old_artists);

            foreach($artists_add as $artist_add)
            {
                $artist = $this->getDoctrine()->getRepository("FPBEBundle:Artist")->findByOneArtistName($artist_add);
                if(!$artist)
                {
                    $artist = new Artist();
                    $artist->setArtistName($artist_add);
                    $artist->setPutLink(false);

                }
                $artist->addProject($item);
                $item->addArtist($artist);
                $em->persist($artist);
            }
        }
//        die(var_dump($files));
        if(!empty($files))
        {
            foreach($files as $index => $file)
            {
                $current_file = new ProjectFile();
                $current_file->setFileFile($files[$index]);
                $current_file->setFileDir($item->getId());
                $item->addFile($current_file);
                $em->persist($item);
            }
        }

        $em->persist($item);
        $em->flush();
        return $this->redirect($this->generateUrl("fp.be.project.get_list"));
    }

    public function postEditVideoAction(Request $request, Project $item)
    {
        $form = $this->createForm(new ProjectVideoType(), new Project());

        $form->handleRequest($request);
        $video = $form->get("video")->getData();

        $item->setVideo($video);
        $em = $this->getDoctrine()->getManager();

        $em->persist($item);
        $em->flush();
        return $this->redirect($this->generateUrl("fp.be.project.get_list"));
    }

    public function postDeleteAction(Request $request, Project $obj)
    {
        $result = $this->get("fpbe.basic_form_handler")->handleDeleteForm($obj, $request);
        return $this->redirect($this->generateUrl("fp.be.project.get_list"));

    }

    public function postProjectPhotosAction(Request $request, Project $project)
    {
        $dm = $this->getDoctrine()->getManager();
        $files = $request->files;

        foreach($files as $file)
        {
            $project_photo = new ProjectPhoto();
            $project_photo->setImageFile($file);
            $project->addPhoto($project_photo);
            $dm->persist($project);
        }
        $dm->flush();
        return $this->redirect($this->generateUrl("fp.be.project.get_edit", array("id" => $project->getId()) ) );
    }

    public function deleteProjectPhotosAction(Request $request, ProjectPhoto $photo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();
        return $this->redirect($this->generateUrl("fp.be.project.get_edit", array("id" => $photo->getProject()->getId())));
    }

    public function deleteProjectPdfAction(ProjectFile $file)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($file);
        $em->flush();
        return $this->redirect($this->generateUrl("fp.be.project.get_edit", array("id" => $file->getProject()->getId())));
    }

}