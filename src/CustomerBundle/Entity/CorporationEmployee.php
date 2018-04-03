<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Person;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
/**
 * CorporationEmployee
 *
 * @ORM\Table(name="corporation_employee")
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CorporationEmployeeRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class CorporationEmployee extends Person
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
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
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
     */
    private $mailAddress;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName", "lastName"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
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
     */
    private $honorific;

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
     * @var CorporationJobStatus
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\CorporationJobStatus", inversedBy="employees", cascade={"persist", "merge"})
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     */
    protected $corporationJobStatus;

    /**
     * @var CorporationSite
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\CorporationSite", inversedBy="employees", cascade={"persist", "merge"})
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_employee"})
     */
    protected $corporationSite;

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
     * @return CorporationEmployee
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
     * @return CorporationEmployee
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
     * @return CorporationEmployee
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
     * @return CorporationEmployee
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
     * @return CorporationEmployee
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
     * @return CorporationEmployee
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
     * @return CorporationEmployee
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
     * @return CorporationEmployee
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
     * @param \CustomerBundle\Entity\CorporationJobStatus $corporationJobStatus
     *
     * @return CorporationEmployee
     */
    public function setCorporationJobStatus(\CustomerBundle\Entity\CorporationJobStatus $corporationJobStatus = null)
    {
        $this->corporationJobStatus = $corporationJobStatus;
        $corporationJobStatus->addEmployee($this);

        return $this;
    }

    /**
     * Get corporationJobStatus
     *
     * @return \CustomerBundle\Entity\CorporationJobStatus
     */
    public function getCorporationJobStatus()
    {
        return $this->corporationJobStatus;
    }

    /**
     * Set corporationSite
     *
     * @param \CustomerBundle\Entity\CorporationSite $corporationSite
     *
     * @return CorporationEmployee
     */
    public function setCorporationSite(\CustomerBundle\Entity\CorporationSite $corporationSite = null)
    {
        $this->corporationSite = $corporationSite;
        $corporationSite->addEmployee($this);

        return $this;
    }

    /**
     * Get corporationSite
     *
     * @return \CustomerBundle\Entity\CorporationSite
     */
    public function getCorporationSite()
    {
        return $this->corporationSite;
    }
}
