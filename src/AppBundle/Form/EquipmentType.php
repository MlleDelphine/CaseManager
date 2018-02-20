<?php

namespace AppBundle\Form;

use AppBundle\Entity\TimePrice;
use AppBundle\Form\Type\CustomTinyMceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                "label_format" => "Intitulé",
                "required" => true))
            ->add("reference", TextType::class, array(
                "label_format" => "Référence unique",
                "required" => true))
            ->add("description", CustomTinyMceType::class, array(
                "label_format" => "Description",
                "configs" => ["height" => 300, "language_url" => "/bundles/app/js/tinymce/langs/fr_FR.js"],
                "required" => false,
                "attr" => ["class" => "tinymce-textarea"]))
            ->add("working", CheckboxType::class, array(
                "label_format" => "Fonctionnel",
                "required" => false,
                "data" => true
            ));
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
