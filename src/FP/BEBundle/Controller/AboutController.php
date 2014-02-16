<?php
namespace FP\BEBundle\Controller;

use FP\BEBundle\Entity\About;
use FP\BEBundle\Form\AboutType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AboutController extends Controller
{
    /**
     * Returning About page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEditAction()
    {
        return $this->render("FPBEBundle:Default/About:about.html.twig");
    }

    /**
     * Returning Form Portion
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPartialEditFormAction()
    {
        $abouts = $this->getDoctrine()->getRepository("FPBEBundle:About")->findAll();

        $about = null;
        if(!empty($abouts))
        {
            $about = array_pop($abouts);
        }
        else
        {
            $about = new About();
            $about->setDescription("description");
            $this->get("fpbe.basic_domain_manager")->saveRecord($about, false);
        }

        $form = $this->get("fpbe.basic_form_handler")->createForm(new AboutType(), $about);

        return $this->render("FPBEBundle:Default/About/partial:about_form.html.twig", array(
            "form" => $form->createView(),
            "about" => $about,
        ));
    }

    public function postEditFormAction(Request $request)
    {
        $abouts = $this->getDoctrine()->getRepository("FPBEBundle:About")->findAll();

        $about = null;
        if(!empty($abouts))
        {
            $about = array_pop($abouts);
        }
        $formHandler = $this->get("fpbe.basic_form_handler");

        $form = $formHandler->createForm(new AboutType(), $about);

        $result = $formHandler->handle($form, $request);

        if($result)
        {
            return $this->redirect($this->generateUrl("fp.be.about.get_edit"));
        }
    }
}