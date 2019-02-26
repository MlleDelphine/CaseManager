<?php
/**
 * Created by PhpStorm.
 * User: DG713C7N
 * Date: 18/02/2019
 * Time: 15:26
 */

namespace Application\Sonata\MediaBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class MediaFileType extends FileType
{

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_media_file_type';
    }
}