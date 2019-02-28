<?php

namespace BusinessBundle\Entity;

use Application\Sonata\MediaBundle\Entity\BusinessCaseMedia;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSer;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BusinessCaseGallery
 *
 * @ORM\Table(name="business_case_gallery_media")
 * @ORM\Entity(repositoryClass="BusinessBundle\Repository\BusinessCaseGalleryRepository")
 */
class BusinessCaseGallery
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DocumentType
     * @ORM\ManyToOne(targetEntity="BusinessBundle\Entity\DocumentType", inversedBy="businessCaseGalleries")
     * @JMSSer\Expose
     * @JMSSer\Groups({"business_export_business_case"})
     *
     * @Assert\NotNull()
     */
    protected $documentType;

    /**
     * @var ArrayCollection|BusinessCaseMedia
     *
     * @ORM\OneToMany(targetEntity="Application\Sonata\MediaBundle\Entity\BusinessCaseMedia", mappedBy="businessCaseGallery", cascade={"all"}, orphanRemoval=true)
     * @JMSSer\Expose
     * @JMSSer\Groups({"business_export_business_case"})
     *
     * @Assert\NotNull()
     * @Assert\Count(min=1, minMessage="Vous devez charger au moins un media")
     * @Assert\All(
     *      @Assert\Type(
     *          type="Application\Sonata\MediaBundle\Entity\BusinessCaseMedia"
     *      )
     * )
     * @Assert\Valid()
     */
    protected $businessCaseMedias;

    /**
     * @var BusinessCase
     * @ORM\ManyToOne(targetEntity="BusinessBundle\Entity\BusinessCase", inversedBy="businessCaseGalleries")
     * @JMSSer\Expose
     * @JMSSer\Groups({"business_export_business_case"})
     */
    protected $businessCase;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;

    public function __construct()
    {
        $this->businessCaseMedias = new ArrayCollection();
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set documentType
     *
     * @param DocumentType $documentType
     *
     * @return BusinessCaseGallery
     */
    public function setDocumentType(DocumentType $documentType = null)
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Get DocumentType
     *
     * @return DocumentType
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

//    public function setBusinessCaseMedias($businessCaseMedias){
//
//        $this->businessCaseMedias = $businessCaseMedias;
//
//        return $this;
//
//    }

    /**
     * Add businessCaseMedia
     *
     * @param BusinessCaseMedia $businessCaseMedia
     *
     * @return BusinessCaseGallery
     */
    public function addBusinessCaseMedia(BusinessCaseMedia $businessCaseMedia)
    {
        $this->businessCaseMedias[] = $businessCaseMedia;
        $businessCaseMedia->setBusinessCaseGallery($this);

        return $this;
    }

    /**
     * Remove businessCaseMedia
     *
     * @param BusinessCaseMedia $businessCaseMedia
     */
    public function removeBusinessCaseMedia(BusinessCaseMedia $businessCaseMedia)
    {
        $this->businessCaseMedias->removeElement($businessCaseMedia);
        //$businessCaseMedia->setBusinessCase(null);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusinessCaseMedias()
    {
        return $this->businessCaseMedias;
    }

    /**
     * Set businessCase
     *
     * @param BusinessCase $businessCase
     *
     * @return BusinessCaseGallery
     */
    public function setBusinessCase(BusinessCase $businessCase = null)
    {
        $this->businessCase = $businessCase;

        return $this;
    }

    /**
     * Get BusinessCase
     *
     * @return BusinessCase
     */
    public function getBusinessCase()
    {
        return $this->businessCase;
    }


    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return BusinessCaseGallery
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return BusinessCaseGallery
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
