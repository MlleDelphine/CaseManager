<?php

namespace AppBundle\Form;

use AppBundle\Entity\TimePrice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, array("label_format" => "Intitulé", "required" => true))
            ->add("description", TextType::class, array("label_format" => "Description", "required" => true))
            ->add("unit", ChoiceType::class, array("label_format" => "Unité de mesure", "required" => true,
                "choices" =>
                    ["Tonne" => "Tonne",
                        "m³" => "m³",
                        "m²" => "m²",
                        "Litre" => "Litre",
                        "Mètre linéaire" => "Mètre linéaire",
                        "m" => "m",
                        "Pièce" => "Pièce"]))
            ->add("timePrices", CollectionType::class, array(
                "entry_type" => TimePriceType::class,
                "entry_options" => ["label" => false],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all TimePrice
                "attr" => [
                    "class" => "item-collection col-md-7 col-xs-12",
                ],
                "data" => [new TimePrice()],
                "label_format" => false,
                "required" => false));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\Material"
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "appbundle_material";
    }


}
