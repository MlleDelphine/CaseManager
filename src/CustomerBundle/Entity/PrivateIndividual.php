<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\AbstractClass\PostalAddressSubjectInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * PrivateIndividual
 *
 *
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\PrivateIndividualRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class PrivateIndividual extends Customer implements PostalAddressSubjectInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=15, nullable=true)
     * @Assert\Regex("/^((\+\d{2})|0)[0-9]{9}$/")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    protected $phoneNumber;

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
    protected $mailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="honorific", type="string", length=10)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     */
    protected $honorific;

    /**
     * Virtual Property for slug generation = __toString()
     * @var string
     */
    protected $name;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

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

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }
    /**
     * @ORM\PrePersist
     */
    public function setVirtualName(){
        $this->name = $this->__toString();
    }

    public function getObjectName() {
        return "private_individual";
    }

    public function getEntityName() {
        return "private_individual";
    }

    public function getHtmlName() {
        return $this->__toString()." (particulier)";
    }

    public function getType() {
        return parent::TYPE_PRIVATE_INDIVIDUAL;
    }
}
