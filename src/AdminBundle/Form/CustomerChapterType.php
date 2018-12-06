<?php

namespace AdminBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerChapterType extends AbstractType
{
    const MODE_BY_SERIAL = "BY_SERIAL";
    const MODE_BY_ITSELF = "BY_ITSELF";
    const MODE_POP_UP = "BY_POP_UP";
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

        if($options["MODE"] == self::MODE_POP_UP){
            $builder
                ->add('customerSerial', Select2EntityType::class, array(
                    "class" => "AdminBundle:CustomerSerial",
                    "choice_translation_domain" => "messages",
                    // "choice_label" => "htmlName",
                    "label_format" => "customer_serial_capitalize",
                    "multiple" => false,
                    "placeholder" => "---",
                    "required" => true,
                    "attr" => ["readonly" => true, "class" => "form-control col-md-12 col-xs-12 disabled" ]
                ));
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
