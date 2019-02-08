<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\AbstractClass\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;
//

/**
 * CustomerContact
 *
 * @ORM\Table(name="customer_contact")
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CustomerContactRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 * @GRID\Source(columns="id, slug, concatenated_full_name, customer.name, corporationJobStatus.name, honorific, firstName, lastName, mailAddress, phoneNumber , created, updated", groups={"default", "general", "merged_address", "merged_address_full_name", "merged_full_name"})
 * @GRID\Column(id="concatenated_full_name", type="civility", title="full_name_capitalize", field="CONCAT(honorific, ' ', lastName, ' ', firstName)", operators={"like"}, isManualField=true, translateCivility=true, source=true)
 */
class CustomerContact extends Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     *
     * @GRID\Column(title="ID", operators={"eq", "neq", "gt", "lt", "gte", "lte"}, defaultOperator="eq", type="number", visible=false, align="left")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee", "corpo_job_status_childrow"})
     *
     * @GRID\Column(title="firstName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     * @GRID\Column(title="firstName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=false, align="left", class="column-title", groups={"merged_full_name"})
     *
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee", "corpo_job_status_childrow"})
     *
     * @GRID\Column(title="lastName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     * @GRID\Column(title="lastName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=false, align="left", class="column-title", groups={"merged_full_name"})
     *
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=15, nullable=true)
     *
     * @Assert\Regex("/^((\+\d{2})|0)[0-9]{9}$/")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     *
     * @GRID\Column(title="phone_number_capitalize", operators={"like", "nlike", "rslike", "llike" }, defaultOperator="like", type="text", visible=true, align="left")
     */
    protected $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="mailAddress", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     *
     * @GRID\Column(title="mail_address_capitalize", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general", "merged_full_name"})
     *
     */
    private $mailAddress;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName", "lastName"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     * @GRID\Column(title="Slug", type="text", visible=false, groups={"default", "general", "merged_full_name"})
     *
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="honorific", type="string", length=10)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     *
     * @GRID\Column(title="honorific_capitalize", operators={"like"}, defaultOperator="like", type="text", visible=true, groups={"general"}, align="left")
     * @GRID\Column(title="honorific_capitalize", operators={"like"}, defaultOperator="like", type="text", visible=false,  groups={"merged_full_name"}, align="left")
     */
    private $honorific;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     *
     * @GRID\Column(title="created_m_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     *
     * @GRID\Column(title="updated_m_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")
     */
    protected $updated;

    /**
     * @var CorporationJobStatus
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\CorporationJobStatus", inversedBy="customerContacts")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     *
     * @GRID\Column(field="corporationJobStatus.name", title="job_capitalize", operators={"like", "nlike"}, defaultOperator="like", visible=true, align="center",  filter="select", groups={"general", "default", "merged_full_name"})
     */
    protected $corporationJobStatus;

    /**
     * If employee deleted, Customer (corpo) no deleted
     * Owning side here
     * @var Customer
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\AbstractClass\Customer", inversedBy="customerContacts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     *
     * @Assert\NotNull()
     *
     * @GRID\Column(field="customer.name", title="customer", operators={"like", "nlike"}, defaultOperator="like", type="text", visible=true, align="center", groups={"general", "default", "merged_full_name"})
     */

    protected $customer;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="BusinessBundle\Entity\BusinessCase", mappedBy="customerContact", fetch="EXTRA_LAZY", cascade={"persist", "merge"}, orphanRemoval=false)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     */
    protected $businessCases;

    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getFirstName()." ".$this->getLastName();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return CustomerContact
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return CustomerContact
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return CustomerContact
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
     * Set mailAddress
     *
     * @param string $mailAddress
     *
     * @return CustomerContact
     */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = $mailAddress;

        return $this;
    }

    /**
     * Get mailAddress
     *
     * @return string
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * Set honorific
     *
     * @param string $honorific
     *
     * @return CustomerContact
     */
    public function setHonorific($honorific)
    {
        $this->honorific = $honorific;

        return $this;
    }

    /**
     * Get honorific
     *
     * @return string
     */
    public function getHonorific()
    {
        return $this->honorific;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return CustomerContact
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
     * @return CustomerContact
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
     * @return CustomerContact
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
     * Set corporationJobStatus
     *
     * @param CorporationJobStatus $corporationJobStatus
     *
     * @return CustomerContact
     */
    public function setCorporationJobStatus(CorporationJobStatus $corporationJobStatus = null)
    {
        $this->corporationJobStatus = $corporationJobStatus;
        if($corporationJobStatus){
            $corporationJobStatus->addCustomerContact($this);
        }

        return $this;
    }

    /**
     * Get corporationJobStatus
     *
     * @return CorporationJobStatus
     */
    public function getCorporationJobStatus()
    {
        return $this->corporationJobStatus;
    }

    /**
     * Set customer
     *
     * @param Customer $customer
     *
     * @return CustomerContact
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;
        $customer->addCustomerContact($this);

        return $this;
    }

    /**
     * Get customer
     *
     * @return \CustomerBundle\Entity\AbstractClass\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    public function getObjectName() {
        return "customerContact";
    }

    public function getHtmlName() {
        return $this->__toString();
    }

    public function getFullName() {
        return $this->lastName." ".$this->firstName;
    }
}
