<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\AbstractClass\CustomerSubjectInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * CorporationSite
 *
 *
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CorporationSiteRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class CorporationSite extends Customer implements CustomerSubjectInterface
{
    /**
     * @var string
     *
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
     * @Assert\Regex("/^((\+\d{2})|0)[0-9]{9}$/")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     */
    protected $phoneNumber;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * @var CorporationGroup
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\CorporationGroup", inversedBy="corporationSites", cascade={"persist", "merge", "detach"})
     *
     * @Assert\NotNull()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     */
    public $corporationGroup;

    /**
     * @var CorporationEmployee[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CorporationEmployee", mappedBy="corporationSite", fetch="EXTRA_LAZY", cascade={"persist", "detach"})
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

//    /**
//     * Set postalAddress
//     *
//     * @param \CustomerBundle\Entity\PostalAddress $postalAddress
//     *
//     * @return CorporationSite
//     */
//    public function setPostalAddress(\CustomerBundle\Entity\PostalAddress $postalAddress = null)
//    {
//        $this->postalAddress = $postalAddress;
//        $postalAddress->setCorporationSite($this);
//
//        return $this;
//    }
//
//    /**
//     * Get postalAddress
//     *
//     * @return \CustomerBundle\Entity\PostalAddress
//     */
//    public function getPostalAddress()
//    {
//        return $this->postalAddress;
//    }

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

    public function getObjectName() {
        return "CorporationSite";
    }
}
