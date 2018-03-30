<?php

namespace CustomerBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorporationEmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstName", TextType::class, array(
                "label_format" => "firstname",
                "required" => true))
            ->add("lastName", TextType::class, array(
                "label_format" => "lastname",
                "required" => true))
            ->add("mailAddress",EmailType::class, array(
                "label_format" => "email"))
            ->add("phoneNumber", TextType::class, array(
                "label_format" => "Téléphone",
                "required" => false,
                "attr" => ["pattern" => "^0[0-9]{9}$"]))
            ->add("CorporationJobStatus",Select2EntityType::class, array(
                "class" => "CustomerBundle:CorporationJobStatus",
                "choice_label" => "name",
                "label_format" => "job",
                "multiple" => false,
                "placeholder" => "select",
                "required" => false))
            ->add("corporationSite", Select2EntityType::class, array(
                "class" => "CustomerBundle:CorporationSite",
                "choice_label" => "name",
                "label_format" => "corporation_site",
                "multiple" => false,
                "placeholder" => "-",
                "required" => false))
            ->add('honorific');
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CustomerBundle\Entity\CorporationEmployee'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'customerbundle_corporationemployee';
    }


}
