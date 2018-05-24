<?php

namespace AdminBundle\Form;

use AppBundle\Form\Type\JqueryDateType;
use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label_format" => "naming_capitalize", "required" => true))
            ->add("percentage", PercentType::class, array(
                "label_format" => "percentage_capitalize",
                "attr" => ["required" => true ],
                "invalid_message" => "Cette valeur doit être un nombre décimal."))
            ->add("fromDate", DateType::class, array(
                "label_format" => "from_capitalize",
                "required" => true,
                "widget" => "single_text"))
            ->add("untilDate", DateType::class, array(
                "label_format" => "until_cod_capitalize",
                "required" => false,
                "widget" => "single_text"));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AdminBundle\Entity\Rate"
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "appbundle_rate";
    }


}
