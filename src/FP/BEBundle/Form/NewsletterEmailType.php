<?php
namespace FP\BEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("address", "email");
//            ->add("submit", "submit", array("label" => "Save"));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'FP\BEBundle\Entity\NewsletterEmail',
        ));
    }

    public function getName()
    {
        return "NewsletterEmail";
    }
}