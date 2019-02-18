<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 15/02/2019
 * Time: 20:28
 */

namespace Application\Sonata\MediaBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryHasMediaType extends AbstractType
{/**
 * {@inheritdoc}
 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                'label' => "Logo"
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
        return "businessbundle_businesscase_galleryhasmedia";
    }


}