<?php
namespace FP\BEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("image_file", "file", array("required" => false))
            ->add("artist_name", "text", array("required" => false))
            ->add("age", "text", array("required" => false))
            ->add("location", "text", array("required" => false))
            ->add("url", "url", array("required" => false))
            ->add("biography", "ckeditor_custom", array("required" => false))
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'FP\BEBundle\Entity\Artist',
        ));
    }

    public function getName()
    {
        return "Artist";
    }
}