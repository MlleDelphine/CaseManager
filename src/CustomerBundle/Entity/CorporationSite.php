<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\AbstractClass\CustomerSubjectInterface;
use CustomerBundle\Entity\CorporationGroup;
use CustomerBundle\Entity\CustomerContact;
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
class CorporationSite extends Customer
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
     * @var CustomerContact[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CustomerContact", mappedBy="corporationSite", fetch="EXTRA_LAZY", cascade={"persist", "detach"})
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
     * @param CorporationGroup $corporationGroup
     *
     * @return CorporationSite
     */
    public function setCorporationGroup(CorporationGroup $corporationGroup = null)
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
     * Add employee
     *
     * @param CustomerContact $employee
     *
     * @return CorporationSite
     */
    public function addEmployee(CustomerContact $employee)
    {
        $this->employees[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param CustomerContact $employee
     */
    public function removeEmployee(CustomerContact $employee)
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

    public function getHtmlName() {
        return $this->__toString()." (site)";
    }

    public function getType() {
        return parent::TYPE_CORPO_SITE;
    }

    public function getTypeName(){
        return "corpo_sites_capitalize";
    }
}
