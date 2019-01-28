<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\AbstractClass\PostalAddressSubjectInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * PrivateIndividual
 *
 *
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\PrivateIndividualRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 * @GRID\Source(columns="id, slug, concatenated_full_name, firstName, lastName, mailAddress, phoneNumber, concatenated_postal_address, postalAddress.country, postalAddress.streetNumber, postalAddress.streetName, postalAddress.complement, postalAddress.postalCode, created, updated", groups={"merged_address_full_name"})
 * @GRID\Column(id="concatenated_postal_address", type="text", title="postal_address", field="CONCAT(postalAddress.streetNumber, ', ', postalAddress.streetName, ' ', postalAddress.postalCode, ' ', postalAddress.city)", operators={"like"}, isManualField=true, source=true, groups={"merged_address_full_name"})
 * @GRID\Column(id="concatenated_full_name", type="civility", title="full_name_capitalize", field="CONCAT(honorific, ' ', lastName, ' ', firstName)", operators={"like"}, isManualField=true, translateCivility=true, source=true, groups={"merged_address_full_name"})
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
     * @GRID\Column(title="firstName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     * @GRID\Column(title="firstName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=false, align="left", class="column-title", groups={"merged_full_name", "merged_address_full_name"})
     *
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
     *
     * @GRID\Column(title="lastName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     * @GRID\Column(title="lastName", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=false, align="left", class="column-title", groups={"merged_full_name", "merged_address_full_name"})
     *
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
     *
     * @GRID\Column(title="phone_number_capitalize", operators={"like", "nlike", "rslike", "llike" }, defaultOperator="like", type="text", visible=true, align="left")
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
     *
     * @GRID\Column(title="mail_address_capitalize", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general", "merged_full_name", "merged_address_full_name"})
     *
     */
    protected $mailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="honorific", type="string", length=10)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_privateindividual"})
     *
     * @GRID\Column(title="honorific_capitalize", operators={"like"}, defaultOperator="like", type="text", visible=true, groups={"general"}, align="left")
     * @GRID\Column(title="honorific_capitalize", operators={"like"}, defaultOperator="like", type="text", visible=false, groups={"merged_full_name", "merged_address_full_name"}, align="left")
     *
     */
    protected $honorific;

    /**
     * Virtual Property for slug generation = __toString()
     * @var string
     *
     * @GRID\Column(title="name", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=false, align="left", class="column-title", groups={"merged_full_name", "merged_address_full_name"})
     *
     */
    protected $name;

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
        $this->setVirtualName();
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

    public function getTypeName(){
        return "private_individuals_capitalize";
    }
}
