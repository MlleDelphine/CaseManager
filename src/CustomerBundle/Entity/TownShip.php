<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * TownShip
 *
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\TownShipRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 * @GRID\Source(columns="id, slug, name, phoneNumber, postalAddress.country, postalAddress.streetNumber, postalAddress.streetName, postalAddress.complement, postalAddress.postalCode, created, updated", groups={"default", "general"})
 * @GRID\Source(columns="id, slug, name, phoneNumber, concatenated_postal_address, postalAddress.country, postalAddress.streetNumber, postalAddress.streetName, postalAddress.complement, postalAddress.postalCode, created, updated", groups={"merged_address"})
 * @GRID\Column(id="concatenated_postal_address", type="text", title="postal_address", field="CONCAT(postalAddress.streetNumber, ', ', postalAddress.streetName, ' ', postalAddress.postalCode, ' ', postalAddress.city)", operators={"like"}, isManualField=true, source=true, groups={"merged_address_full_name"})
 * @GRID\Column(id="concatenated_full_name", type="civility", title="full_name_capitalize", field="CONCAT(honorific, ' ', lastName, ' ', firstName)", operators={"like"}, isManualField=true, translateCivility=true, source=true, groups={"merged_address_full_name"})
 *
 */
class TownShip extends Customer
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_township"})
     *
     * @GRID\Column(title="name_capitalize", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"default", "general", "merged_address"})
     *
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=15, nullable=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_township"})
     *
     * @GRID\Column(title="phone_number_capitalize", operators={"like", "nlike", "rslike", "llike" }, defaultOperator="like", type="text", visible=true, align="left", groups={"default", "general", "merged_address"})
     */
    private $phoneNumber;

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
     * @return TownShip
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
     * @return TownShip
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

    public function getHtmlName() {
        return $this->__toString()." (commune)";
    }

    public function getType() {
        return parent::TYPE_TOWN_SHIP;
    }

    public function getTypeName(){
        return "townships_capitalize";
    }
}

