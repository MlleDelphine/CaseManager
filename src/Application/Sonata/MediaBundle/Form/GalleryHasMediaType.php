<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: Delphine
 * Date: 15/02/2019
 * Time: 20:28
=======
 * User: DG713C7N
 * Date: 18/02/2019
 * Time: 14:17
>>>>>>> 6ddcbd16c50329cf083f9b5d472c1915b2294d39
 */

namespace Application\Sonata\MediaBundle\Form;

use Application\Sonata\MediaBundle\Form\Type\GalleryHasMediaNameTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryHasMediaType extends AbstractType

{/**
 * {@inheritdoc}
 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add("name", GalleryHasMediaNameTextType::class, [
                "label_format" => "filename_media_capitalize",
                "attr" => ["class" => "form-control col-sm-12"] //form-control
            ])
            ->add("description", TextareaType::class, [
                "label_format" => "description_capitalize",
                "attr" => ["class" => "form-control tinymce-textarea col-sm-12"] //form-control
            ])
            ->add("position", NumberType::class, [
                "label_format" => "position_capitalize",
                "attr" => ["class" => "form-control col-sm-12 media-position"]
            ])
            ->add('media', BusinessCaseMediaType::class, [
                //'provider' => 'sonata.media.provider.image',
                'provider' => 'sonata.media.provider.file',
//                'provider' => [
//                    'sonata.media.provider.image',
//                    'sonata.media.provider.dailymotion',
//                    'sonata.media.provider.youtube',
//                    'sonata.media.provider.file',
//                    'sonata.media.provider.vimeo'
//                ],
                'context' => 'business_case_media_context',
                'data_class'   =>  'Application\Sonata\MediaBundle\Entity\Media',
                'required' => false,
                'by_reference' => true,
                'label' => false,
                "attr" => ["class" => "media-file-input"]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "Application\Sonata\MediaBundle\Entity\GalleryHasMedia"
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "galleryHasMedias_form";
    }


}

