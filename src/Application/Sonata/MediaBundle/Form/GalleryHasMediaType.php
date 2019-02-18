<?php
/**
 * Created by PhpStorm.
 * User: DG713C7N
 * Date: 18/02/2019
 * Time: 14:17
 */

namespace Application\Sonata\MediaBundle\Form;


use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryHasMediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('media',MediaType::class, array(
                    'provider' => 'sonata.media.provider.youtube',
                    'context'  => 'business_case_document')
            )
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
