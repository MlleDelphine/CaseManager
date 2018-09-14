<?php

namespace AppBundle\Form;

use AppBundle\Entity\TimePrice;
use AppBundle\Form\Type\CustomTinyMceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceType extends AbstractType
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
            ->add("unit", ChoiceType::class, array("label_format" => "Unité de mesure", "required" => true,
                "choices" =>
                    ["Tonne" => "Tonne",
                        "m³" => "m³",
                        "m²" => "m²",
                        "Litre" => "Litre",
                        "Mètre linéaire" => "Mètre linéaire",
                        "m" => "m",
                        "Pièce" => "Pièce"]));
        if ($options["MODE_CREATE"]){
            $builder->add("timePrices", CollectionType::class, array(
                "entry_type" => TimePriceType::class,
                "entry_options" => ["label" => false],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all TimePrice
                "attr" => [
                    "class" => "item-collection col-md-12 col-xs-12",
                ],
                "label_format" => "prices_definition_capitalize",
                "required" => false));
        }
        else{
            $builder->add("timePrices", CollectionType::class, array(
                "entry_type" => TimePriceType::class,
                "entry_options" => ["label" => false],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all TimePrice
                "attr" => [
                    "class" => "item-collection col-md-12 col-xs-12",
                ],
                "label_format" => "prices_definition_capitalize",
                "required" => false));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\Resource",
            "MODE_CREATE" => self::MODE_CREATE
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "appbundle_resource";
    }


}
