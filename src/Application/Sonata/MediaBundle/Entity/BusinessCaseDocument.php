<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 22/11/2018
 * Time: 15:59
 */

namespace Application\Sonata\MediaBundle\Entity;


use BusinessBundle\Entity\BusinessCase;
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
     * @var int
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

}