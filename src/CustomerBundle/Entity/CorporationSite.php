<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Customer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * CorporationSite
 *
 *
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CorporationSiteRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 * @GRID\Source(columns="id, slug, name,corporationGroup.name, phoneNumber, created, updated", groups={"general"})
 * @GRID\Source(columns="id, slug, name,corporationGroup.name, phoneNumber, concatenated_postal_address, created, updated", groups={"general", "merged_address"})
 * @GRID\Column(id="concatenated_postal_address", type="text", title="postal_address", field="CONCAT(postalAddress.streetNumber, ', ', postalAddress.streetName, ' ', postalAddress.postalCode, ' ', postalAddress.city)", operators={"like"}, isManualField=true, source=true, groups={"merged_address"})
 */
class CorporationSite extends Customer
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite", "corpo_site_childrow"})
     *
     * @GRID\Column(title="name", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general", "merged_address"})
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=15, nullable=true)
     * @Assert\Regex("/^((\+\d{2})|0)[0-9]{9}$/")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     *
     * @GRID\Column(title="phone_number_capitalize", operators={"like", "nlike", "rslike", "llike" }, defaultOperator="like", type="text", visible=true, align="left")
     */
    protected $phoneNumber;

//    /**
//     * @var \DateTime
//     * @Gedmo\Timestampable(on="update")
//     */
//    protected $updated;

    /**
     * @var CorporationGroup
     * @ORM\ManyToOne(targetEntity="CustomerBundle\Entity\CorporationGroup", inversedBy="corporationSites", cascade={"persist", "merge", "detach"})
     *
     * @Assert\NotNull()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationsite"})
     *
     * @GRID\Column(field="corporationGroup.name", title="corpo_group_capitalize", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general", "merged_address"})
     */
    public $corporationGroup;

    /**
     * @var CustomerContact[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CustomerContact", mappedBy="customer", fetch="EXTRA_LAZY", cascade={"persist", "detach"})
     */
    protected $customerContacts;

    public function __construct()
    {
        $this->customerContacts = new ArrayCollection();
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
        $corporationGroup->addCorporationSite($this);

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
     * Add customerContact
     *
     * @param CustomerContact $customerContact
     *
     * @return CorporationSite
     */
    public function addCustomerContact(CustomerContact $customerContact)
    {
        $this->customerContacts[] = $customerContact;

        return $this;
    }

    /**
     * Remove customerContact
     *
     * @param CustomerContact $customerContact
     */
    public function removeCustomerContact(CustomerContact $customerContact)
    {
        $this->customerContacts->removeElement($customerContact);
    }

    /**
     * Get customerContacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerContacts()
    {
        return $this->customerContacts;
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
