<?php
/**
 * Created by PhpStorm.
 * User: DG713C7N
 * Date: 18/02/2019
 * Time: 15:26
 */

namespace Application\Sonata\MediaBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GalleryHasMediaCollectionType extends CollectionType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'galleryHasMedias_collection_form';
    }
}