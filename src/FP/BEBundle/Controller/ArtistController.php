<?php
namespace FP\BEBundle\Controller;

use FP\BEBundle\Entity\Artist;
use FP\BEBundle\Form\ArtistType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArtistController extends Controller
{
    public function getListAction(Request $request)
    {
        $artists = $this->get("fpbe.basic_form_handler")->handleSearchForm($request, "artist");
        return $this->render("FPBEBundle:Default/Artist:artist.html.twig", array("artists" => $artists));
    }

    public function getPartialArtistListAction($artists = null)
    {
        $items = $artists === null ? $this->getDoctrine()->getRepository("FPBEBundle:Artist")->findAll() : $artists;
        $delete_form = $this->get("fpbe.basic_form_handler")->createDeleteForm();

        return $this->render("FPBEBundle:Default/Artist/partial:artist_list.html.twig", array(
                "items" => $items,
                "delete_form" => $delete_form->createView(),
            ));
    }

    public function getNewAction()
    {
        $form = $this->createForm(new ArtistType(), new Artist());
        return $this->render("FPBEBundle:Default/Artist:artist_new.html.twig", array(
            "form" => $form->createView(),
        ));
    }


    public function postNewAction(Request $request)
    {
        $form = $this->createForm(new ArtistType(), new Artist());

        $formHandler = $this->get("fpbe.basic_form_handler");
        $result = $formHandler->handle($form, $request);

        if($result)
        {
            return $this->redirect($this->generateUrl("fp.be.artist.get_list"));
        }
        else
        {
            return $this->render("@FPBE/Default/Artist/artist_new.html.twig", array(
                "form" => $form->createView(),
            ));
        }
    }

    public function getEditAction(Artist $item)
    {
        return $this->render("FPBEBundle:Default/Artist:artist_edit.html.twig", array("item" => $item));
    }

    public function getPartialEditFormAction($item)
    {
        $form = $this->get("fpbe.basic_form_handler")->createForm(new ArtistType(), $item);
        return $this->render("FPBEBundle:Default/Artist/partial:artist_edit_form.html.twig", array(
            "edit_form" => $form->createView(),
            "item" => $item,
        ));
    }

    public function postEditAction(Request $request, Artist $item)
    {
        $form = $this->createForm(new ArtistType(), $item);
        $result = $this->get("fpbe.basic_form_handler")->handle($form, $request);
        return $this->redirect($this->generateUrl("fp.be.artist.get_list"));
    }

    public function postDeleteAction(Request $request, Artist $obj)
    {
        $result = $this->get("fpbe.basic_form_handler")->handleDeleteForm($obj, $request);
        return $this->redirect($this->generateUrl("fp.be.artist.get_list"));

    }

}