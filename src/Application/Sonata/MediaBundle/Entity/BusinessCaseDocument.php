<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 22/11/2018
 * Time: 15:59
 */

namespace Application\Sonata\MediaBundle\Entity;


use BusinessBundle\Entity\BusinessCase;
use BusinessBundle\Entity\DocumentType;
use Sonata\MediaBundle\Entity\BaseMedia;

class BusinessCaseDocument extends BaseMedia {

    /**
     * @var int
     *
     */
    protected $id;

    /**
     * @var BusinessCase
     */
    protected $businessCase;

    /**
     * @var DocumentType
     * @ORM\ManyToOne(targetEntity="BusinessBundle\Entity\DocumentType", inversedBy="businessCaseDocuments")
     *
     */
    protected $type;

    /**
     * @return int
     */
    public function getId() {
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

    public function setBinaryContent($binaryContent)
    {
        if(!$this->providerReference && $this->previousProviderReference){
            $this->providerReference = $this->previousProviderReference;
        }
        //  $this->providerReference = null;
        $this->binaryContent = $binaryContent;
    }

    public function getBase64Image(){

    }

    /**
     * Set user
     *
     * @param DocumentType $documentType
     *
     * @return BusinessCaseDocument
     */
    public function setDocumentType(DocumentType $documentType = null)
    {
        $this->type = $documentType;

        return $this;
    }

    /**
     * Get DocumentType
     *
     * @return DocumentType
     */
    public function getDocumentType()
    {
        return $this->type;
    }

}