<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\AbstractClass\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitTimePointType extends AbstractType
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
//            ->add("customerArticles",  Select2EntityType::class, array(
//                "class" => "AdminBundle:CustomerArticles",
//                "choice_label" => "name",
//                "label_format" => "application_domain_capitalize",
//                "multiple" => true,
//                "placeholder" => "-",
//                "required" => true))
            ->add("unitaryPoint", IntegerType::class, array(
                "label_format" => "points_capitalize",
                "required" => true,
                "invalid_message" => "error_message_decimal_number"));


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            $form ->add("unit", ChoiceType::class, array(
                "label_format" => "measure_unit_capitalize",
                "required" => true,
                "choices" => Unit::getAllUnits(),
                "data" => $event->getData() ? $event->getData()->getUnit() : "Pièce"));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\UnitTimePoint'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_unittimepoint';
    }


}
