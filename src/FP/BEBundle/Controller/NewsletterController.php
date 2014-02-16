<?php
namespace FP\BEBundle\Controller;

use FP\BEBundle\Entity\About;
use FP\BEBundle\Entity\Newsletter;
use FP\BEBundle\Entity\NewsletterEmail;
use FP\BEBundle\Form\AboutType;
use FP\BEBundle\Form\NewsletterEmailType;
use FP\BEBundle\Form\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends Controller
{
    public function getEditAction()
    {
        $this->getDoctrine()->getRepository("FPBEBundle:NewsletterEmail")->markEmailsRead();
        return $this->render("FPBEBundle:Default/Newsletter:newsletter.html.twig");
    }

    public function getPartialEditFormAction()
    {
        $newsletter = $this->getDoctrine()->getRepository("FPBEBundle:Newsletter")->findAll();

        if(!empty($newsletter))
        {
            $newsletter = array_pop($newsletter);
        }
        else
        {
            $newsletter = new Newsletter();
            $newsletter->setDescription("description");
            $this->get("fpbe.basic_domain_manager")->saveRecord($newsletter, false);
        }

        $form = $this->get("fpbe.basic_form_handler")->createForm(new NewsletterType(), $newsletter);

        $emails = $this->getDoctrine()->getRepository("FPBEBundle:NewsletterEmail")->findAll();

        return $this->render("FPBEBundle:Default/Newsletter/partial:newsletter_form.html.twig", array(
            "form" => $form->createView(),
            "newsletter" => $newsletter,
            "emails" => $emails,
        ));
    }

    public function postEditFormAction(Request $request)
    {
        $newsletter = $this->getDoctrine()->getRepository("FPBEBundle:Newsletter")->findAll();

        if(!empty($newsletter))
        {
            $newsletter = array_pop($newsletter);
        }
        $formHandler = $this->get("fpbe.basic_form_handler");

        $form = $formHandler->createForm(new NewsletterType(), $newsletter);

        $result = $formHandler->handle($form, $request);

        if($result)
        {
            return $this->redirect($this->generateUrl("fp.be.newsletter.get_edit"));
        }
    }

    public function postEmailAction(Request $request, $id)
    {
        $newsletter = $this->getDoctrine()->getRepository("FPBEBundle:Newsletter")->find($id);
        $form = $this->createForm(new NewsletterEmailType(), new NewsletterEmail());
        $form->handleRequest($request);
        $email = $form->getData();
        $newsletter->addEmail($email);
        $email->setNewsletter($newsletter);
        $this->getDoctrine()->getManager()->persist($email);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("fpfe_contact"));
    }

    public function getGenerateEmailTextAction(Request $request, $id)
    {
        $emails = $this->getDoctrine()->getRepository("FPBEBundle:NewsletterEmail")->findAll();

        if(!file_exists("newsletter_emails"))
        {
            $old_umask = umask(0);
            mkdir("newsletter_emails" ,0777, true);
            umask($old_umask);
        }

        $handler = fopen("newsletter_emails/emails.txt", "w");
        foreach($emails as $key=>$value)
        {
            fwrite($handler, $value->getAddress() . "\r\n");
        }

        $response = new Response();
        $response->setContent(file_get_contents("newsletter_emails/emails.txt"));
        $response->headers->set("Content-Type", "application/octet-stream");
        $response->headers->set('Content-Disposition', 'attachment; filename="emails.txt"');
        return $response;
    }

    public function getNewEmailsAction()
    {
        $num = $this->getDoctrine()->getRepository("FPBEBundle:NewsletterEmail")->getNewEmailsNumber();
        if($num == null)
        {
            $num = null;
        }

        return $this->render("@FPBE/Default/Layout/notification.html.twig", array("newEmailsNumber" => $num));
    }

    public function postEmailsDeleteAction(Request $request)
    {
        $inner_ids = $request->request->has("inner_ids") ? $request->request->get("inner_ids") : null;
        $em = $this->getDoctrine()->getManager();
        if($inner_ids){
            foreach($inner_ids as $item_id)
            {
                $menu_item = $em->getRepository("FPBEBundle:NewsletterEmail")->find($item_id);

                if(!$menu_item)
                {
                    return $this->createNotFoundException("Menu Item not found");
                }
                $em->remove($menu_item);
            }
        }

        $em->flush();

        return $this->redirect($this->generateUrl("fp.be.newsletter.get_edit"));
    }

}