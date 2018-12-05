<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerChapterType extends AbstractType
{
    const MODE_BY_SERIAL = "BY_SERIAL";
    const MODE_BY_ITSELF = "BY_ITSELF";
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                "label" => "naming_capitalize",
                "required" => true));

        if($options["MODE"] == self::MODE_BY_ITSELF){
            $builder->add("customerSerial");
        }


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\CustomerChapter',
            "MODE" => self::MODE_BY_SERIAL
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_customerchapter';
    }


}
