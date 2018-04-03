<?php

namespace CustomerBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorporationSiteType extends AbstractType
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
            ->add("phoneNumber", TextType::class, array(
                "label_format" => "phone_number",
                "required" => false,
                "attr" => ["pattern" => "^((\+\d{2})|0)[0-9]{9}$"],
                "translation_domain" => "messages"))
            ->add("corporationGroup",  Select2EntityType::class, array(
                "class" => "CustomerBundle:CorporationGroup",
                "choice_label" => "name",
                "label_format" => "corporation_group_parent",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true,
                "translation_domain" => "messages"))
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
            'data_class' => 'CustomerBundle\Entity\CorporationSite'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'customerbundle_corporationsite';
    }


}
