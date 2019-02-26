<?php

namespace BusinessBundle\Entity;

use Application\Sonata\MediaBundle\Entity\BusinessCaseDocument;
use Application\Sonata\MediaBundle\Entity\BusinessCaseGallery;
use Assetic\Cache\ArrayCache;
use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\CustomerContact;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SecurityAppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * BusinessCase
 *
 * @ORM\Table(name="business_case")
 * @ORM\Entity(repositoryClass="BusinessBundle\Entity\Repository\BusinessCaseRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class BusinessCase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_case"})
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
     * @ORM\Column(name="description", type="text", nullable=true)
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_case"})
     */
    private $description;

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
     * @ORM\Column(name="externalReference", type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_case"})
     */
    private $externalReference;

    /**
     * @var string
     *
     * @ORM\Column(name="internalReference", type="string", length=255, nullable=true, unique=true)
     */
    private $internalReference;

//    /**
//     * @var string
//     *
//     * @ORM\Column(name="customer_type", type="string", length=25, nullable=false, unique=false)
//     *
//     * @Assert\NotBlank()
//     * @JMSSer\Expose()
//     * @JMSSer\Groups({"business_export_business_case"})
//     */
//    private $customerType;

    /**
     * @var Customer
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\AbstractClass\Customer", inversedBy="businessCases", cascade={"persist", "merge", "detach"})
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_case"})
     */
    protected $customer;

    /**
     * @var CustomerContact
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\CustomerContact", inversedBy="businessCases", cascade={"persist", "merge", "detach"})
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_case"})
     */
    protected $customerContact;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="SecurityAppBundle\Entity\User", inversedBy="businessCases", cascade={"persist", "merge", "detach"})
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_case"})
     */
    protected $user;

    /**
     * @var BusinessCaseDocument
     * @ORM\OneToMany(targetEntity="Application\Sonata\MediaBundle\Entity\BusinessCaseDocument", mappedBy="businessCase", cascade={"all"}, orphanRemoval=true)
     * @JMSSer\Expose
     * @JMSSer\Groups({"business_export_business_case"})
     */
    protected $documents;

    /**
     * @var ArrayCollection[BusinessCaseGallery]
     * @ORM\OneToMany(targetEntity="Application\Sonata\MediaBundle\Entity\BusinessCaseGallery", mappedBy="businessCase", cascade={"all"}, orphanRemoval=true)
     * @JMSSer\Expose
     * @JMSSer\Groups({"business_export_business_case"})
     */
    protected $businessCaseGalleries;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="BusinessBundle\Entity\ConstructionSitePostalAddress", inversedBy="businessCase", cascade={"all"}, orphanRemoval=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_customers", "admin_export_corporationgroup", "admin_export_corporationsite"})
     *
     */
    protected $constructionSitePostalAddress;

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
     * @return BusinessCase
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
     * Set description
     *
     * @param string $description
     *
     * @return BusinessCase
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return BusinessCase
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
     * Set externalReference
     *
     * @param string $externalReference
     *
     * @return BusinessCase
     */
    public function setExternalReference($externalReference)
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    /**
     * Get externalReference
     *
     * @return string
     */
    public function getExternalReference()
    {
        return $this->externalReference;
    }

    /**
     * Set internalReference
     *
     * @param string $internalReference
     *
     * @return BusinessCase
     */
    public function setInternalReference($internalReference)
    {
        $this->internalReference = $internalReference;

        return $this;
    }

    /**
     * Get internalReference
     *
     * @return string
     */
    public function getInternalReference()
    {
        return $this->internalReference;
    }

    public function getCustomerType(){
        return $this->getCustomer()->getType();
    }

    /**
     * Set customer
     *
     * @param Customer $customer
     *
     * @return BusinessCase
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set customerContact
     *
     * @param CustomerContact|null $customerContact
     * @return BusinessCase
     */
    public function setCustomerContact(CustomerContact $customerContact = null)
    {
        $this->customerContact = $customerContact;

        return $this;
    }

    /**
     * Get customerContact
     *
     * @return CustomerContact
     */
    public function getCustomerContact()
    {
        return $this->customerContact;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return BusinessCase
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * Set constructionSitePostalAddress
     *
     * @param ConstructionSitePostalAddress $postalAddress
     *
     * @return BusinessCase
     */
    public function setConstructionSitePostalAddress(ConstructionSitePostalAddress $postalAddress = null)
    {
        $this->constructionSitePostalAddress = $postalAddress;
        $postalAddress->setBusinessCase($this);

        return $this;
    }

    /**
     * Get constructionSitePostalAddress
     *
     * @return ConstructionSitePostalAddress
     */
    public function getConstructionSitePostalAddress()
    {
        return $this->constructionSitePostalAddress;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return BusinessCase
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
     * @return BusinessCase
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
     *
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
     * Get businessCaseGalleries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusinessCaseGalleries()
    {
        return $this->businessCaseGalleries;
    }
}

