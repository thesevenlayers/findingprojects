<?php
namespace FP\BEBundle\Controller;

use FP\BEBundle\Entity\About;
use FP\BEBundle\Entity\HomeItem;
use FP\BEBundle\Form\HomeItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function getListAction()
    {
        return $this->render("FPBEBundle:Default/Home:home.html.twig");
    }

    public function getNewAction()
    {
        $form = $this->createForm(new HomeItemType(), new HomeItem());
        return $this->render("FPBEBundle:Default/Home:home_new.html.twig", array(
            "form" => $form->createView(),
        ));
    }

    public function getPartialHomeListAction()
    {
        $items = $this->getDoctrine()->getRepository("FPBEBundle:HomeItem")->findAll();
        $delete_form = $this->get("fpbe.basic_form_handler")->createDeleteForm();

        return $this->render("FPBEBundle:Default/Home/partial:home_list.html.twig", array(
            "items" => $items,
            "delete_form" => $delete_form->createView(),
        ));
    }

    public function postNewAction(Request $request)
    {
       $form = $this->createForm(new HomeItemType(), new HomeItem());

        $formHandler = $this->get("fpbe.basic_form_handler");
        $result = $formHandler->handle($form, $request);

        if($result)
        {
            return $this->redirect($this->generateUrl("fp.be.home.get_list"));
        }
        else
        {
            return $this->render("@FPBE/Default/Home/home_new.html.twig", array(
                "form" => $form->createView(),
            ));
        }
    }

    public function getEditAction($id)
    {
        $item = $this->getDoctrine()->getRepository("FPBEBundle:HomeItem")->find($id);
        if(!$item){
            throw $this->createNotFoundException("Item not found");
        }
        return $this->render("@FPBE/Default/Home/home_edit.html.twig", array("item" => $item));

    }

    public function getPartialEditFormAction($item)
    {
        $form = $this->get("fpbe.basic_form_handler")->createForm(new HomeItemType(), $item);
        return $this->render("@FPBE/Default/Home/partial/home_edit_form.html.twig", array(
            "edit_form" => $form->createView(),
            "item" => $item,
        ));
    }

    public function postEditAction(Request $request, $id)
    {
        $item = $this->getDoctrine()->getRepository("FPBEBundle:HomeItem")->find($id);
        if(!$item)
        {
            throw $this->createNotFoundException("Item not found");
        }
        $form = $this->createForm(new HomeItemType(), $item);
        $result = $this->get("fpbe.basic_form_handler")->handle($form, $request);
        return $this->redirect($this->generateUrl("fp.be.home.get_list"));
    }

    public function postDeleteAction(Request $request, $id)
    {
        $obj = $this->getDoctrine()->getRepository("FPBEBundle:HomeItem")->find($id);
        $result = $this->get("fpbe.basic_form_handler")->handleDeleteForm($obj, $request);
//        var_dump($this->get("fpbe.basic_form_handler")->errors);
//        die;
        return $this->redirect($this->generateUrl("fp.be.home.get_list"));

    }
}