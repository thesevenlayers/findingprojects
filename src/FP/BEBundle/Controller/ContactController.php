<?php
namespace FP\BEBundle\Controller;

use FP\BEBundle\Entity\Contact;
use FP\BEBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function getEditAction()
    {
        return $this->render("FPBEBundle:Default/Contact:contact.html.twig");
    }

    public function getPartialEditFormAction()
    {
        $contacts = $this->getDoctrine()->getRepository("FPBEBundle:Contact")->findAll();

        $contact = null;
        if(!empty($contacts))
        {
            $contact = array_pop($contacts);
        }
        else
        {
            $contact = new Contact();
            $contact->setAddress("address");
            $this->get("fpbe.basic_domain_manager")->saveRecord($contact, false);
        }

        $form = $this->get("fpbe.basic_form_handler")->createForm(new ContactType(), $contact);

        return $this->render("@FPBE/Default/Contact/partial/contact_form.html.twig", array(
            "form" => $form->createView(),
            "contact" => $contact,
        ));
    }

    public function postEditFormAction(Request $request)
    {
        $contacts = $this->getDoctrine()->getRepository("FPBEBundle:Contact")->findAll();

        $contact = null;
        if(!empty($contacts))
        {
            $contact = array_pop($contacts);
        }
        $formHandler = $this->get("fpbe.basic_form_handler");

        $form = $formHandler->createForm(new ContactType(), $contact);

        $result = $formHandler->handle($form, $request);

        if($result)
        {
            return $this->redirect($this->generateUrl("fp.be.contact.get_edit"));
        }
    }
}