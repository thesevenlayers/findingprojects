<?php
namespace FP\BEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("address", "text", array("required" => false))
            ->add("phone", "text", array("required" => false))
            ->add("email", "text", array("required" => false))
            ->add("facebook", "text", array("required" => false))
            ->add("vimeo", "text", array("required" => false))
            ->add("zip", "text", array("required" => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'FP\BEBundle\Entity\Contact',
        ));
    }

    public function getName()
    {
        return "Contact";
    }
}