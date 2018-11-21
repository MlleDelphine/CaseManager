<?php

namespace CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherCustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label_format" => "name_capitalize",
                "required" => true,
                "translation_domain" => "messages"))
            ->add("phoneNumber", TextType::class, array(
                "label_format" => "phone_number_capitalize",
                "required" => true,
                "attr" => ["pattern" => "^((\+\d{2})|0)[0-9]{9}$"])) //  "^0[0-9]{9}$"
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
            'data_class' => 'CustomerBundle\Entity\OtherCustomer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'customerbundle_other_customer';
    }
}
