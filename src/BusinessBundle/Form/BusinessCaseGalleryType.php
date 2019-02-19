<?php

namespace BusinessBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use Application\Sonata\MediaBundle\Form\GalleryHasMediaType;
use Application\Sonata\MediaBundle\Form\Type\GalleryHasMediaCollectionType;
use Symfony\Component\Form\AbstractType;
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
            ->add('galleryHasMedias', GalleryHasMediaCollectionType::class, array(
                "entry_type" => GalleryHasMediaType::class,
                "entry_options" => ["label" => false],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all BusinessCaseGallery
                "attr" => [
                    "class" => "item-collection-multiple-media col-md-12 col-xs-12",
                ],
                "label_format" => "media_galleryhasmedia_capitalize",
                "required" => false));
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
        return "businesscase_gallery";
    }


}
