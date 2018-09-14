<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Person;
use CustomerBundle\Entity\AbstractClass\PostalAddressSubjectInterface;
use CustomerBundle\Entity\PostalAddress;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * PrivateIndividual
 *
 * @ORM\Table(name="private_individual")
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\PrivateIndividualRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class PrivateIndividual extends Person implements PostalAddressSubjectInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=15, nullable=true)
     * @Assert\Regex("/^((\+\d{2})|0)[0-9]{9}$/")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="mailAddress", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    private $mailAddress;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName", "lastName"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="honorific", type="string", length=10)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
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
     * @var PostalAddress
     * @ORM\OneToOne(targetEntity="CustomerBundle\Entity\PostalAddress", inversedBy="privateIndividual", cascade={"all"}, orphanRemoval=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    protected $postalAddress;

    public function __construct()
    {
    }

    public function __toString() {
        return (string) $this->getFirstName()." ".$this->getLastName();
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
     * @return PrivateIndividual
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
     * @return PrivateIndividual
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
     * @return PrivateIndividual
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
     * @return PrivateIndividual
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
     * Set slug
     *
     * @param string $slug
     *
     * @return PrivateIndividual
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
     * Set honorific
     *
     * @param string $honorific
     *
     * @return PrivateIndividual
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
     * Set postalAddress
     *
     * @param PostalAddress $postalAddress
     *
     * @return PrivateIndividual
     */
    public function setPostalAddress(PostalAddress $postalAddress = null)
    {
        $this->postalAddress = $postalAddress;
        $postalAddress->setPrivateIndividual($this);

        return $this;
    }

    /**
     * Get postalAddress
     *
     * @return PostalAddress
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return PrivateIndividual
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
     * @return PrivateIndividual
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

    public function getObjectName() {
        return "PrivateIndividual";
    }

    public function getEntityName() {
        return "PrivateIndividual";
    }
}
