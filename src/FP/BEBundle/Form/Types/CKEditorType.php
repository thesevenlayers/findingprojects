<?php
namespace FP\BEBundle\Form\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CKEditorType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "config" => array(
                'toolbar' => array(
                    array(
                        'name'  => 'document',
                        'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo',
                            '-', 'SpellChecker',
                            '-', 'Link','Unlink','Anchor',
                            '-', 'Table', 'HorizontalRule', 'SpecialChar', 'Maximize', 'Source', 'About'),
                    ),
                    '/',
                    array(
                        'name'  => 'basicstyles',
                        'items' => array('Bold', 'Italic', 'Underline', 'Strike',
                            '-', 'RemoveFormat',
                            '-', 'NumberedList','BulletedList',
                            '-', 'Outdent','Indent',
                            '-', 'Blockquote',
                            '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock',
                            '-', 'TextColor',
                            '-', 'Styles','Format'),
                    ),
                ),
            ),
        ));
    }

    public function getName()
    {
        return "ckeditor_custom";
    }

    public function getParent()
    {
        return "ckeditor";
    }
}