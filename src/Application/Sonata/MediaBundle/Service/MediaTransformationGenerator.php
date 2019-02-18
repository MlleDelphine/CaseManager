<?php
/**
 * Created by PhpStorm.
 * User: FU923DGR
 * Date: 06/10/2017
 * Time: 11:23
 */

namespace Application\Sonata\MediaBundle\Service;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\Pool;

class MediaTransformationGenerator
{
    /**
     * @var Pool
     */
    protected $pool;


    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    public function getAbsolutePath(MediaInterface $media, $format = "reference"){

        $provider = $this->pool->getProvider($media->getProviderName());
        $fileFormat = $provider->getFormatName($media, $format);
        $relativePath = $provider->generatePrivateUrl($media, $fileFormat);
        $absoluteRoot = $provider->getFilesystem()->getAdapter()->getDirectory();

        return $absoluteRoot."/".$relativePath;
    }

    public function getBaseEncode64(MediaInterface $media, $format = "reference"){
        $absolutePath = $this->getAbsolutePath($media, $format);

        return base64_encode(file_get_contents($absolutePath));
    }

//    public function getMediaFileFormat(){
//
//    }

}