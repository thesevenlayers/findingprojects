<?php
namespace FP\BEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("description", "ckeditor_custom");
//            ->add("submit", "submit", array("label" => "Save"));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'FP\BEBundle\Entity\Newsletter',
        ));
    }

    public function getName()
    {
        return "Newsletter";
    }
}