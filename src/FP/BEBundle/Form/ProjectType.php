<?php
namespace FP\BEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Thrace\FormBundle\Form\DataTransformer\ArrayToStringTransformer;
use Thrace\FormBundle\Form\Type\Select2Type;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", "text", array("required" => false))
            ->add("description", "ckeditor_custom", array("required" => false))
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'FP\BEBundle\Entity\Project',
            ));
    }

    public function getName()
    {
        return "Project";
    }
}