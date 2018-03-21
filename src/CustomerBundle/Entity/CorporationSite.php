<?php

namespace CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * CorporationSite
 *
 * @ORM\Table(name="corporation_site")
 * @ORM\Entity(repositoryClass="CustomerBundle\Repository\CorporationSiteRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class CorporationSite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=15, nullable=true)
     * @Assert\Regex("/^0[0-9]{9}$/")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     */
    protected $phoneNumber;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

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
     * @var CorporationGroup
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\CorporationGroup", inversedBy="corporationSites", cascade={"persist", "merge", "detach"})
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     */
    public $corporationGroup;

    /**
     * @var PostalAddress
     * @ORM\OneToOne(targetEntity="CustomerBundle\Entity\PostalAddress", inversedBy="corporationSite", cascade={"all"}, orphanRemoval=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     */
    protected $postalAddress;

    /**
     * @var CorporationEmployee[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CorporationEmployee", mappedBy="coprorationSite", fetch="EXTRA_LAZY", cascade={"persist", "detach"})
     */
    protected $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
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
     * @return CorporationSite
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return CorporationSite
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return CorporationSite
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
     * @return CorporationSite
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
     * @return CorporationSite
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
     * Set corporationGroup
     *
     * @param \CustomerBundle\Entity\CorporationGroup $corporationGroup
     *
     * @return CorporationSite
     */
    public function setCorporationGroup(\CustomerBundle\Entity\CorporationGroup $corporationGroup = null)
    {
        $this->corporationGroup = $corporationGroup;

        return $this;
    }

    /**
     * Get corporationGroup
     *
     * @return CorporationGroup
     */
    public function getCorporationGroup()
    {
        return $this->corporationGroup;
    }

    /**
     * Set postalAddress
     *
     * @param \CustomerBundle\Entity\PostalAddress $postalAddress
     *
     * @return CorporationSite
     */
    public function setPostalAddress(\CustomerBundle\Entity\PostalAddress $postalAddress = null)
    {
        $this->postalAddress = $postalAddress;
        $postalAddress->setCorporationSite($this);

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

    /**
     * Add employee
     *
     * @param \CustomerBundle\Entity\CorporationEmployee $employee
     *
     * @return CorporationSite
     */
    public function addEmployee(\CustomerBundle\Entity\CorporationEmployee $employee)
    {
        $this->employees[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param \CustomerBundle\Entity\CorporationEmployee $employee
     */
    public function removeEmployee(\CustomerBundle\Entity\CorporationEmployee $employee)
    {
        $this->employees->removeElement($employee);
    }

    /**
     * Get employees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployees()
    {
        return $this->employees;
    }
}
