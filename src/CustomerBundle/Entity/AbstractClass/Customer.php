<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 14/09/2018
 * Time: 18:16
 */

namespace CustomerBundle\Entity\AbstractClass;

use BusinessBundle\Entity\BusinessCase;
use CustomerBundle\Entity\CustomerContact;
use CustomerBundle\Entity\PostalAddress;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMSSer;

/**
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CustomerRepository")
 * @ORM\Table(name="customer")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "corporationGroup" = "CustomerBundle\Entity\CorporationGroup",
 *     "corporationSite" = "CustomerBundle\Entity\CorporationSite",
 *     "privateIndividual" = "CustomerBundle\Entity\PrivateIndividual",
 *     "townShip" = "CustomerBundle\Entity\TownShip",
 *     "otherCustomer" = "CustomerBundle\Entity\OtherCustomer"
 *     })
 *
 * @ORM\HasLifecycleCallbacks()
 */
abstract class Customer extends Person implements CustomerSubjectInterface{

    const TYPE_CORPO_GROUP = "corporationGroup";
    const TYPE_CORPO_SITE = "corporationSite";
    const TYPE_PRIVATE_INDIVIDUAL = "privateIndividual";
    const TYPE_TOWN_SHIP = "townShip";
    const TYPE_OTHER_CUSTOMER = "otherCustomer";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMSSer\Expose
     * @JMSSer\Groups({"admin_export_customers"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="slug", length=128, unique=true)
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     */
    protected $slug;

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
     * @var
     * @ORM\OneToOne(targetEntity="CustomerBundle\Entity\PostalAddress", inversedBy="customer", cascade={"all"}, orphanRemoval=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_customers", "admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $postalAddress;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CustomerContact", mappedBy="customer", fetch="EXTRA_LAZY", cascade={"persist", "merge", "remove"})
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     */
    protected $customerContacts;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="BusinessBundle\Entity\BusinessCase", mappedBy="customer", fetch="EXTRA_LAZY", cascade={"persist", "merge", "remove"})
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     */
    protected $businessCases;

    public function __construct() {
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     *
     * Set postalAddress
     *
     * @param PostalAddress $postalAddress
     *
     * @return Customer
     */
    public function setPostalAddress(PostalAddress $postalAddress = null)
    {
        $this->postalAddress = $postalAddress;
        $postalAddress->setCustomer($this);

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
     * Add customerContact
     *
     * @param CustomerContact $customerContact
     *
     * @return Customer
     */
    public function addCustomerContact(CustomerContact $customerContact)
    {
        $this->customerContacts[] = $customerContact;

        return $this;
    }

    /**
     * Remove corporationSite
     *
     * @param \CustomerBundle\Entity\CustomerContact $customerContact
     */
    public function removeCustomerContact(CustomerContact $customerContact)
    {
        $this->customerContacts->removeElement($customerContact);
    }

    /**
     * Add businessCase
     *
     * @param BusinessCase $businessCase
     *
     * @return Customer
     */
    public function addBusinessCase(BusinessCase $businessCase)
    {
        $this->businessCases[] = $businessCase;

        return $this;
    }

    /**
     * Remove corporationSite
     *
     * @param BusinessCase $businessCase
     */
    public function removeBusinessCase(BusinessCase $businessCase)
    {
        $this->businessCases->removeElement($businessCase);
    }

    abstract public function getType();

}