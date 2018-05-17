<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\JqueryDateType;
use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimePriceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("fromDate", DateType::class,
                array("label_format" => "A partir de",
                    "required" => true,
                    "widget" => "single_text"))
            ->add("untilDate", DateType::class,
                array("label_format" => "until_cod_capitalize",
                    "required" => false,
                    "widget" => "single_text"))
            ->add("unitaryPrice", MoneyType::class,
                array("label_format" => "Coût",
                    "attr" => ["required" => true, "pattern" => "^\d+(,|.)\d{2}$"],
                    "invalid_message" => "Cette valeur doit être un nombre décimal."));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\TimePrice"
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "appbundle_timeprice";
    }


}
