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



use Sonata\MediaBundle\Form\Type\MediaType;
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
            ])
            ->add("position");
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
        return "galleryHasMedias";
    }


}

