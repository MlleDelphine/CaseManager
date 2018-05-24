<?php

namespace AppBundle\Form;

use AdminBundle\Entity\AbstractClass\Unit;
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
                "label_format" => "from_capitalize",
                "required" => true,
                "widget" => "single_text"))
            ->add("untilDate", DateType::class, array(
                "label_format" => "until_cod_capitalize",
                "required" => false,
                "widget" => "single_text"))
            ->add("unit", ChoiceType::class, array(
                "label_format" => "measure_unit_capitalize",
                "required" => true,
                "choices" => Unit::getAllUnits()))
            ->add("constructionSiteTypes",  Select2EntityType::class, array(
                "class" => "AdminBundle:ConstructionSiteType",
                "choice_label" => "name",
                "label_format" => "application_domain_capitalize",
                "multiple" => true,
                "placeholder" => "-",
                "required" => true))
            ->add("unitaryPrice", MoneyType::class, array(
                "label_format" => "cost_capitalize",
                "attr" => ["required" => true, "pattern" => "^\d+(,|.)\d{2}$"],
                "currency" => "", //To avoid orphan €
                "invalid_message" => "error_message_decimal_number"));

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
