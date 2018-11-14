<?php

namespace BusinessBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\CorporationGroup;
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
     * @JMSSer\Groups({"admin_export_timeprice"})
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
     * @JMSSer\Groups({"admin_export_material", "admin_export_timeprice"})
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
     * @var string
     *
     * @ORM\Column(name="externalReference", type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_timeprice"})
     */
    private $externalReference;

    /**
     * @var string
     *
     * @ORM\Column(name="internalReference", type="string", length=255, unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="CustomerBundle\Service\InternalReferenceGenerator")
     */
    private $internalReference;

    /**
     * @var Customer
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\AbstractClass\Customer", inversedBy="businessCases", cascade={"persist", "merge", "detach"})
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_equipment"})
     */
    protected $customer;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="SecurityAppBundle\Entity\User", inversedBy="businessCases", cascade={"persist", "merge", "detach"})
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_equipment"})
     */
    protected $user;

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
     * @ORM\PrePersist()
     */
    public function generateInternalReference(){
        //No ref exists
        if(!$this->internalReference || !preg_match("/[a-z]/", $this->internalReference)){
            if($this->customer->getObjectName() == CorporationGroup::ObjectName){
                $lastSixDigitsOfExternalReference = preg_match("/\d{6}$/", $this->externalReference, $m);
                $projectManagerName = $this->customerProjectManager;
                $projectManagerNameCleaned = substr(strtoupper(preg_replace("/[^a-zA-Z]/","", $projectManagerName)), 0,3);

                $firstThreeLetters = str_pad($projectManagerNameCleaned, 3, "X");
                if(isset($m[0])) {
                    $internalReference = "EC".date("y").$lastSixDigitsOfExternalReference.$firstThreeLetters;
                    $this->internalReference = $internalReference;
                }
            } else{
                $internalReference = "E".date("ym").str_pad($this->internalReference, 6, 0, STR_PAD_LEFT);
                $this->internalReference = $internalReference;
            }
        }
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


    /**
     * Set equipment
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
     * Set user
     *
     * @param User $user
     *
     * @return BusinessCase
     */
    public function setEquipment(User $user = null)
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
}

