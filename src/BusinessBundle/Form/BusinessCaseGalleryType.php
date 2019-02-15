<?php

namespace BusinessBundle\Form;

use AdminBundle\Entity\AbstractClass\Unit;
use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessCaseGalleryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('galleryType', Select2EntityType::class, array(
                "class" => "BusinessBundle\Entity\DocumentType",
                //"choices" => [],
                "choice_label" => "name",
                "label_format" => "document_type_capitalize",
                "multiple" => false,
                "placeholder" => "select_type_before_capitalize",
                "required" => true
            ))
            ->add('galleryHasMedias');

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "Application\Sonata\MediaBundle\Entity\BusinessCaseGallery"
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "businessbundle_businesscase_gallery";
    }


}
