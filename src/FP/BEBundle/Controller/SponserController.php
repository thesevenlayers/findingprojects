<?php
namespace FP\BEBundle\Controller;

use FP\BEBundle\Entity\Project;
use FP\BEBundle\Entity\Sponser;
use FP\BEBundle\Form\SponserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SponserController extends Controller
{
    public function getListAction()
    {
        $sponsers = $this->getDoctrine()->getRepository("FPBEBundle:Sponser")->findAll();
        return $this->render("FPBEBundle:Default/Sponser:sponsers_index.html.twig", array(
                "items" => $sponsers,
            ));
    }

    public function getPartialSponsersListAction()
    {
        $items = $this->getDoctrine()->getRepository("FPBEBundle:Sponser")->getGlobalSponsers();
        $delete_form = $this->get("fpbe.basic_form_handler")->createDeleteForm();

        return $this->render("FPBEBundle:Default/Sponser/partial:sponsers_index_content.html.twig", array(
                "sponsers" => $items,
                "delete_form" => $delete_form->createView(),
            ));
    }


    public function getEditAction(Sponser $item)
    {
        return $this->render("FPBEBundle:Default/Sponser:sponsers_edit.html.twig", array("item" => $item));
    }

    public function getPartialEditFormAction($item)
    {
        $form = $this->get("fpbe.basic_form_handler")->createForm(new SponserType(), $item);
        return $this->render("FPBEBundle:Default/Sponser/partial:sponsers_edit_form.html.twig", array(
                "edit_form" => $form->createView(),
                "item" => $item,
            ));
    }

    public function postEditAction(Request $request, Sponser $item)
    {
        $form = $this->createForm(new SponserType(), $item);
        $result = $this->get("fpbe.basic_form_handler")->handle($form, $request);
        return $this->redirect($this->generateUrl("fp.be.sponser.get_list"));
    }

    public function getAddAction()
    {
        $form = $this->createForm(new SponserType(), new Sponser());
        return $this->render("@FPBE/Default/Sponser/sponsers_new.html.twig", array(
                "form" => $form->createView(),
            ));
    }

    public function postAddAction(Request $request)
    {
        $sponser = new Sponser();
        $form = $this->createForm(new SponserType(), $sponser);
        $form->handleRequest($request);
        $dm = $this->getDoctrine()->getManager();
        $dm->persist($sponser);
        $dm->flush();
        return $this->redirect($this->generateUrl("fp.be.sponser.get_list"));
    }

    public function postAddToProjectAction(Request $request, Project $project)
    {
        $sponser = new Sponser();
        $form = $this->createForm(new SponserType(), $sponser);
        $form->handleRequest($request);
        $dm = $this->getDoctrine()->getManager();
        $project->addSponser($sponser);
        $dm->persist($project);
        $dm->flush();
        return $this->redirect($this->generateUrl("fp.be.project.get_edit", array("id" => $project->getId())));
    }

    public function postDeleteSponserAction(Request $request, Sponser $sponser)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($sponser);
        $em->flush();
        return $this->redirect($this->generateUrl("fp.be.sponser.get_list"));
    }

    public function deleteProjectSponserAction(Sponser $sponser)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($sponser);
        $em->flush();
        return $this->redirect($this->generateUrl("fp.be.project.get_edit", array("id" => $sponser->getProject()->getId())));
    }

}