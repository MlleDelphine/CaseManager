<?php

namespace CustomerBundle\Form;

use AppBundle\Form\Type\Select2ChoiceType;
use AppBundle\Form\Type\Select2EntityType;
use CustomerBundle\Entity\CorporationEmployee;
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
                "label_format" => "phone_number",
                "required" => true,
                "attr" => ["pattern" => "^((\+\d{2})|0)[0-9]{9}$"])) //  "^0[0-9]{9}$"
            ->add("corporationJobStatus",Select2EntityType::class, array(
                "class" => "CustomerBundle:CorporationJobStatus",
                "choice_label" => "name",
                "label_format" => "job",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true))
            ->add("corporationSite", Select2EntityType::class, array(
                "class" => "CustomerBundle:CorporationSite",
                "choice_label" => "name",
                "label_format" => "corporation_site",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true))
            ->add('honorific', Select2ChoiceType::class, array(
                "label_format" => "honorific",
                "required" => true,
                "placeholder" => "select",
                "choices" => CorporationEmployee::getAllHonorifics()
            ));
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
