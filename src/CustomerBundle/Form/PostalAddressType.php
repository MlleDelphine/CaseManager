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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostalAddressType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("streetNumber", TextType::class, array(
                "label_format" => "address_street_number_capitalize",
                "required" => true))
            ->add("streetName", TextType::class, array(
                "label_format" => "address_street_name_capitalize",
                "required" => true))
            ->add("complement", TextType::class, array(
                "label_format" => "address_complement_capitalize",
                "required" => false))
            ->add("postalCode", TextType::class, array(
                "label_format" => "address_postal_code_capitalize",
                "required" => true))
            ->add("city", TextType::class, array(
                "label_format" => "address_city_capitalize",
                "required" => true))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            $form->add("country", CountrySelect2Type::class, array(
                "label_format" => "address_country_capitalize",
                "required" => true,
                "placeholder" => "select",
                "data" => $event->getData() ? $event->getData()->getCountry() : "FR"));
        });
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