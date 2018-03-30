<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Corporation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * CorporationGroup
 *
 * @ORM\Table(name="corporation_group")
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CorporationGroupRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class CorporationGroup extends Corporation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="legalStatus", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $legalStatus;

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

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CorporationSite", mappedBy="corporationGroup", fetch="EXTRA_LAZY", cascade={"persist", "detach"})
     */
    protected $corporationSites;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="CustomerBundle\Entity\PostalAddress", inversedBy="corporationGroup", cascade={"all"}, orphanRemoval=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $postalAddress;

    public function __construct()
    {
        $this->corporationSites = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

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
     * @return CorporationGroup
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
     * @return CorporationGroup
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
     * Set legalStatus
     *
     * @param string $legalStatus
     *
     * @return CorporationGroup
     */
    public function setLegalStatus($legalStatus)
    {
        $this->legalStatus = $legalStatus;

        return $this;
    }

    /**
     * Get legalStatus
     *
     * @return string
     */
    public function getLegalStatus()
    {
        return $this->legalStatus;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CorporationGroup
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
     * @return CorporationGroup
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
     * Add corporationSite
     *
     * @param \CustomerBundle\Entity\CorporationSite $corporationSite
     *
     * @return CorporationGroup
     */
    public function addCorporationSite(\CustomerBundle\Entity\CorporationSite $corporationSite)
    {
        $this->corporationSites[] = $corporationSite;

        return $this;
    }

    /**
     * Remove corporationSite
     *
     * @param \CustomerBundle\Entity\CorporationSite $corporationSite
     */
    public function removeCorporationSite(\CustomerBundle\Entity\CorporationSite $corporationSite)
    {
        $this->corporationSites->removeElement($corporationSite);
    }

    /**
     * Get corporationSites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporationSites()
    {
        return $this->corporationSites;
    }

    /**
     * Set postalAddress
     *
     * @param \CustomerBundle\Entity\PostalAddress $postalAddress
     *
     * @return CorporationGroup
     */
    public function setPostalAddress(\CustomerBundle\Entity\PostalAddress $postalAddress = null)
    {
        $this->postalAddress = $postalAddress;
        $postalAddress->setCorporationGroup($this);

        return $this;
    }

    /**
     * Get postalAddress
     *
     * @return \CustomerBundle\Entity\PostalAddress
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }
}
