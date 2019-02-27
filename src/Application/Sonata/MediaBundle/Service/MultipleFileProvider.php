<?php
/**
 * Created by PhpStorm.
 * User: DG713C7N
 * Date: 27/02/2019
 * Time: 11:06
 */

namespace Application\Sonata\MediaBundle\Service;

use Application\Sonata\MediaBundle\Form\Type\MediaFileType;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\FileProvider;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\File\File;

class MultipleFileProvider extends FileProvider
{
//    /**
//     * @param MediaInterface $media
//     */
//    protected function doTransform(MediaInterface $media)
//    {
//        // ...
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function generatePublicUrl(MediaInterface $media, $format)
//    {
//        // new logic
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function postPersist(MediaInterface $media)
//    {
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function postUpdate(MediaInterface $media)
//    {
//    }

    /**
     * {@inheritdoc}
     */
    public function buildMediaType(FormBuilder $formBuilder)
    {
        if ('api' == $formBuilder->getOption('context')) {
            $formBuilder->add('binaryContent', FileType::class);
            $formBuilder->add('contentType');
        } else {
            $formBuilder->add('binaryContent', MediaFileType::class, [
                'required' => false,
                'label' => 'widget_label_binary_content',
            ]);
        }
    }
}
