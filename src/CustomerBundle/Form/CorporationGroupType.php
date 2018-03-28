<?php

namespace CustomerBundle\Form;

use AppBundle\Form\Type\Select2ChoiceType;
use CustomerBundle\Entity\CorporationGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorporationGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label_format" => "name",
                "required" => true,
                "translation_domain" => "messages"))
            ->add("legalStatus", Select2ChoiceType::class, array(
                "label_format" => "legal_status",
                "required" => true,
                "choices" =>
                    CorporationGroup::getAllLegalStatus()))
            ->add('postalAddress', PostalAddressType::class, array(
                "label_format" => null,
                "required" => true
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CustomerBundle\Entity\CorporationGroup'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'customerbundle_corporationgroup';
    }


}
