<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\CustomTinyMceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentType extends AbstractType
{
    const MODE_CREATE = true;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label_format" => "title_capitalize",
                "required" => true))
            ->add("reference", TextType::class, array(
                "label_format" => "unique_reference_capitalize",
                "required" => true))
            ->add("description", CustomTinyMceType::class, array(
                "label_format" => "description_capitalize",
                "configs" => ["height" => 300, "language_url" => "/bundles/app/js/tinymce/langs/fr_FR.js"],
                "required" => false,
                "attr" => ["class" => "tinymce-textarea"]))
            ->add("working", CheckboxType::class, array(
                "label_format" => "Fonctionnel",
                "required" => false,
                "data" => true
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
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\Equipment",
            "MODE_CREATE" => self::MODE_CREATE
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "appbundle_equipment";
    }


}
