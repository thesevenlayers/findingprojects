<?php
namespace FP\BEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HomeItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("image_file", "file", array("required" => false))
            ->add("artist_name", "text", array("required" => false))
            ->add("project_name", "text", array("required" => false))
            ->add("description", "textarea", array("required" => false));
//            ->add("submit", "submit", array("label" => "Save"));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'FP\BEBundle\Entity\HomeItem',
        ));
    }

    public function getName()
    {
        return "HomeItem";
    }
}