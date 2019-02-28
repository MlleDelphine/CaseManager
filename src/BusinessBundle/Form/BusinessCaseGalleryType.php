<?php

namespace BusinessBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use Application\Sonata\MediaBundle\Form\BusinessCaseMediaType;
use Application\Sonata\MediaBundle\Form\DataTransformer\BusinessCaseMediaTransformer;
use Application\Sonata\MediaBundle\Form\GalleryHasMediaType;
use Application\Sonata\MediaBundle\Form\Type\GalleryHasMediaCollectionType;
use Application\Sonata\MediaBundle\Form\Type\MediaFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessCaseGalleryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('galleryType', Select2EntityType::class, array(
//                "class" => "BusinessBundle\Entity\DocumentType",
//                //"choices" => [],
//                "choice_label" => "name",
//                "label_format" => "document_type_capitalize",
//                "multiple" => false,
//                "placeholder" => "select_type_before_capitalize",
//                "required" => true
//            ))
//            ->add('galleryHasMedias', GalleryHasMediaCollectionType::class, array(
//                "entry_type" => GalleryHasMediaType::class,
//                "entry_options" => ["label" => false, "attr" => ["class" => "col-md-6"]],
//                "allow_add" => true,
//                "allow_delete" => true,
//                "delete_empty" => true,
//                "prototype" => true,
//                "by_reference" => false, //ensures that the setter is called in all BusinessCaseGallery
//                "attr" => [
//                    "class" => "item-collection-multiple-media col-md-12 col-xs-12",
//                ],
//                "label_format" => "media_galleryhasmedia_capitalize",
//                "required" => false));

            ->add('documentType', Select2EntityType::class, array(
                "class" => "BusinessBundle\Entity\DocumentType",
                //"choices" => [],
                "choice_label" => "name",
                "label_format" => "document_type_capitalize",
                "multiple" => false,
                "placeholder" => "select_type_before_capitalize",
                "required" => true
            ))
            ->add('businessCaseMedias', GalleryHasMediaCollectionType::class, array(
                "entry_type" => BusinessCaseMediaType::class,
                "entry_options" => [
                    "label" => false,
                    "attr" => ["multiple" => true],
                    "provider" => "sonata.media.provider.multiple_file",
                    "context" => "business_case_media_context",
                    "data_class" => "Application\Sonata\MediaBundle\Entity\BusinessCaseMedia",
                ],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all BusinessCaseMedias
                "attr" => [
                    "class" => "item-collection-multiple-media multiple-media col-md-12 col-xs-12",
                ],
                'prototype_name' => '__children_name__',
                "label_format" => "media_galleryhasmedia_capitalize",
                "required" => false));



        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            //Not yet reverseTransformed, files still as array
            dump($event->getForm());
            dump($event->getData());
//            die();
        });
//            ->add('businessCaseMedias', MediaFileType::class, array(
//                "attr" => [
//                    "class" => "multiple-media file col-md-12 col-xs-12", //item-collection-multiple-media
//                    'multiple' => 'multiple'
//                ],
//                "multiple" => true,
//                "label_format" => "media_galleryhasmedia_capitalize",
//                "required" => false));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            //   "data_class" => "Application\Sonata\MediaBundle\Entity\BusinessCaseGallery"
            "data_class" => "BusinessBundle\Entity\BusinessCaseGallery"
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
