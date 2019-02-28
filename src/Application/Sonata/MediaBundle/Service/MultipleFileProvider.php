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
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @param MediaInterface $media
     */
    protected function fixBinaryContent(MediaInterface $media)
    {
        dump("fixBinaryContent");
        dump($media);
        //$media instanceof BusinessCaseMedia
        foreach ($media->getBinaryContent() as $k => $mediaFile) {
            dump("Loop over mediaFile");
            echo $k;
            if ($media->getBinaryContent() === null || empty($media->getBinaryContent()) || $mediaFile instanceof File) {
                return;
            }
            dump("Not a file");
            if ($mediaFile instanceof Request) {
                $this->generateBinaryFromRequest($media);
                $this->updateMetadata($media);

                return;
            }

            // if the binary content is a filename => convert to a valid File
            if (!is_file($media->getBinaryContent())) {
                throw new \RuntimeException('The file does not exist : '.$media->getBinaryContent());
            }

            $binaryContent = new File($media->getBinaryContent());
            $media->setBinaryContent($binaryContent);
        }
    }
//
//    /**
//     * Set media binary content according to request content.
//     *
//     * @param MediaInterface $media
//     */
//    protected function generateBinaryFromRequest(MediaInterface $media, $mediaFileKey)
//    {
//        if (php_sapi_name() === 'cli') {
//            throw new \RuntimeException('The current process cannot be executed in cli environment');
//        }
//
//        if (!$media->getContentType()) {
//            throw new \RuntimeException(
//                'You must provide the content type value for your media before setting the binary content'
//            );
//        }
//
//        $request = $media->getBinaryContent();
//
//        if (!$request instanceof Request) {
//            throw new \RuntimeException('Expected Request in binary content');
//        }
//
//        $content = $request->getContent();
//
//        // create unique id for media reference
//        $guesser = ExtensionGuesser::getInstance();
//        $extension = $guesser->guess($media->getContentType());
//
//        if (!$extension) {
//            throw new \RuntimeException(
//                sprintf('Unable to guess extension for content type %s', $media->getContentType())
//            );
//        }
//
//        $handle = tmpfile();
//        fwrite($handle, $content);
//        $file = new ApiMediaFile($handle);
//        $file->setExtension($extension);
//        $file->setMimetype($media->getContentType());
//
//        $media->setBinaryContent($file);
//    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        dump("doTransform multipleFileProvider");
        $this->fixBinaryContent($media);
        $this->fixFilename($media);

        if ($media->getBinaryContent() instanceof UploadedFile && 0 === $media->getBinaryContent()->getClientSize()) {
            $media->setProviderReference(uniqid($media->getName(), true));
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            throw new UploadException('The uploaded file is not found');
        }

        // this is the name used to store the file
        if (!$media->getProviderReference() ||
            $media->getProviderReference() === MediaInterface::MISSING_BINARY_REFERENCE
        ) {
            $media->setProviderReference($this->generateReferenceName($media));
        }

        if ($media->getBinaryContent() instanceof File) {
            $media->setContentType($media->getBinaryContent()->getMimeType());
            $media->setSize($media->getBinaryContent()->getSize());
        }

        $media->setProviderStatus(MediaInterface::STATUS_OK);
    }
}
