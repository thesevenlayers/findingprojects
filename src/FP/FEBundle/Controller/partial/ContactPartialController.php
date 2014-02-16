<?php
namespace FP\FEBundle\Controller\partial;

use FP\BEBundle\Entity\NewsletterEmail;
use FP\BEBundle\Form\NewsletterEmailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactPartialController extends Controller
{
    public function getContactContentAction(Request $request)
    {
        $contact = $this->getDoctrine()->getRepository("FPBEBundle:Contact")->findAll();
        $contact = array_pop($contact);
        $newsletter = $this->getDoctrine()->getRepository("FPBEBundle:Newsletter")->findAll();
        $newsletter = array_pop($newsletter);
        $email_form = $this->createForm(new NewsletterEmailType(), new NewsletterEmail());


        $content = $this->renderView("FPFEBundle:partial:contact_content.html.twig", array(
                "contact" => $contact,
                "newsletter" => $newsletter,
                "form" => $email_form->createView(),
            ));

        $response = new Response();
        $response->setEtag(md5($content));
        $response->isNotModified($request);
        $response->setContent($content);

        return $response;

    }
}