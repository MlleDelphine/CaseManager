<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\JqueryDateType;
use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitTimePriceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("fromDate", DateType::class, array(
                "label_format" => "A partir de",
                "required" => true,
                "widget" => "single_text"))
            ->add("untilDate", DateType::class, array(
                "label_format" => "{{ "until_coi_capitalize"|trans|capitalize }}",
                "required" => false,
                "widget" => "single_text"))
            ->add("unit", ChoiceType::class, array(
                "label_format" => "measure_unit_capitalize",
                "required" => true,
                "choices" =>
                    [
                        "Temps" => ["Heure" => "Heure",
                            "Journée" => "Journée"],
                        "Quantité" => ["Tonne" => "Tonne",
                            "m³" => "m³",
                            "m²" => "m²",
                            "Litre" => "Litre",
                            "Mètre linéaire" => "Mètre linéaire",
                            "m" => "m",
                            "Pièce" => "Pièce"]
                    ]))
            ->add("unitaryPrice", MoneyType::class, array(
                "label_format" => "Coût",
                "attr" => ["required" => true, "pattern" => "^\d+(,|.)\d{2}$"],
                "invalid_message" => "Cette valeur doit être un nombre décimal."));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\UnitTimePrice"
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "appbundle_unittimeprice";
    }


}