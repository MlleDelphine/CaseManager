<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 28/03/2018
 * Time: 14:11
 */

namespace CustomerBundle\Form;

use AppBundle\Form\Type\CountrySelect2Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostalAddressType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("streetNumber", TextType::class, array(
                "label_format" => "address_street_number",
                "required" => true))
            ->add("streetName", TextType::class, array(
                "label_format" => "address_street_name",
                "required" => true))
            ->add("complement", TextType::class, array(
                "label_format" => "address_complement",
                "required" => false))
            ->add("postalCode", TextType::class, array(
                "label_format" => "address_postal_code",
                "required" => true))
            ->add("city", TextType::class, array(
                "label_format" => "address_city",
                "required" => true))
            ->add("country", CountrySelect2Type::class, array(
                "label_format" => "address_country",
                "required" => true,
                "placeholder" => "select"));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CustomerBundle\Entity\PostalAddress'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'customerbundle_postaladdress';
    }
}