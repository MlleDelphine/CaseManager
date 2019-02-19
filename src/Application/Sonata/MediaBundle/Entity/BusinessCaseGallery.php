<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity;

use BusinessBundle\Entity\BusinessCase;
use BusinessBundle\Entity\DocumentType;
use Sonata\MediaBundle\Entity\BaseGallery as BaseGallery;

/**
 * This file has been generated by the Sonata EasyExtends bundle.
 *
 * @link https://sonata-project.org/bundles/easy-extends
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class BusinessCaseGallery extends BaseGallery
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var BusinessCase
     */
    protected $businessCase;

    /**
     * @var DocumentType
     *
     */
    protected $galleryType;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set businessCase
     *
     * @param BusinessCase $businessCase
     * @return void
     */
    public function setBusinessCase(BusinessCase $businessCase = null)
    {
        $this->businessCase = $businessCase;
    }
    /**
     * Get businessCase
     *
     * @return BusinessCase
     */
    public function getBusinessCase()
    {
        return $this->businessCase;
    }
//
//    public function setBinaryContent($binaryContent)
//    {
//        if(!$this->providerReference && $this->previousProviderReference){
//            $this->providerReference = $this->previousProviderReference;
//        }
//        //  $this->providerReference = null;
//        $this->binaryContent = $binaryContent;
//    }
//
//    public function getBase64Image(){
//
//    }

    /**
     * Set user
     *
     * @param DocumentType $documentType
     *
     * @return BusinessCaseGallery
     */
    public function setGalleryType(DocumentType $documentType = null)
    {
        $this->galleryType = $documentType;

        return $this;
    }

    /**
     * Get DocumentType
     *
     * @return DocumentType
     */
    public function getGalleryType()
    {
        return $this->galleryType;
    }

}