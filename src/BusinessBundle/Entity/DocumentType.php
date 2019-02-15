<?php

namespace BusinessBundle\Entity;

use Application\Sonata\MediaBundle\Entity\BusinessCaseGallery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * DocumentType
 *
 * @ORM\Table(name="document_type")
 * @ORM\Entity(repositoryClass="BusinessBundle\Entity\Repository\DocumentTypeRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class DocumentType
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_case"})
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     */
    protected $slug;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var ArrayCollection|BusinessCaseGallery
     *
     * @ORM\OneToMany(targetEntity="Application\Sonata\MediaBundle\Entity\BusinessCaseGallery", mappedBy="businessCase", cascade={"all"}, orphanRemoval=true)
     * @JMSSer\Expose
     * @JMSSer\Groups({"business_export_business_case"})
     */
    protected $businessCaseGalleries;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return DocumentType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return DocumentType
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return DocumentType
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return DocumentType
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add businessCaseGallery
     *
     * @param BusinessCaseGallery $businessCaseGallery
     *
     * @return void
     */
    public function addBusinessCaseGallery(BusinessCaseGallery $businessCaseGallery)
    {
        $this->businessCaseGalleries[] = $businessCaseGallery;
        //$user->setTeam($this);
    }

    /**
     * Remove businessCaseGallery
     *
     * @param BusinessCaseGallery $businessCaseGallery
     */
    public function removeBusinessCaseGallery(BusinessCaseGallery $businessCaseGallery)
    {
        $this->businessCaseGalleries->removeElement($businessCaseGallery);
        //$businessCaseGallery->setBusinessCase(null);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusinessCaseGalleries()
    {
        return $this->businessCaseGalleries;
    }
}

