<?php

namespace AdminBundle\Form;

use AppBundle\Form\Type\CustomTinyMceType;
use AppBundle\Form\UnitTimePriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestationType extends AbstractType
{
    const EDIT_MODE = "EDITION";
    const CREATE_MODE = "CREATION";
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                "label" => "naming_capitalize",
                "required" => true))
            ->add("description", CustomTinyMceType::class, array(
                "label_format" => "description_capitalize",
                "configs" => ["height" => 300, "language_url" => "/bundles/app/js/tinymce/langs/fr_FR.js"],
                "required" => false,
                "attr" => ["class" => "tinymce-textarea"]))
            ->add("color", TextType::class, array(
                "label_format" => "tag_color_capitalize",
                "required" => true,
                "attr" => ["class" => "input-group colorpicker-element"]
            ))
            ->add("unitTimePrices", CollectionType::class, array(
                "entry_type" => UnitTimePriceType::class,
                "entry_options" => ["label" => false],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all UnitTimePrices
                "attr" => [
                    "class" => "item-collection col-md-12 col-xs-12",
                ],
                "label_format" => "prices_definition_capitalize",
                "required" => false));

        if($options['mode'] == self::EDIT_MODE){
            $builder->add('internalReference', TextType::class, array(
                "label_format" => "internal_reference_capitalize",
                "required" => true,
                "attr" => ["readonly" => true]));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\Prestation',
            "mode" => self::CREATE_MODE
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_prestation';
    }


}
