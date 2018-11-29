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
use APY\DataGridBundle\Grid\Mapping as GRID;

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
 *
 * @GRID\Column(id="concatenated_postal_address2", type="join", title="With join as attribute", source=true, columns={"postalAddress.streetNumber", "postalAddress.streetName", "postalAddress.postalCode", "postalAddress.city"}, separator=" ", groups={"merged_address"})
 *
 */
abstract class Customer extends Person implements CustomerSubjectInterface{

    //postalAddress.streetNumber, postalAddress.streetName, postalAddress.complement, postalAddress.postalCode, postalAddress.country
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
     *
     * @GRID\Column(title="ID", operators={"eq", "neq", "gt", "lt", "gte", "lte"}, defaultOperator="eq", type="number", visible=false, align="left")
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
     * @GRID\Column(title="Slug", type="text", visible=false, groups={"default", "general", "merged_address"})
     */
    protected $slug;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     *
     * @GRID\Column(title="created_f_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     *
     * @GRID\Column(title="updated_f_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")

     */
    protected $updated;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="CustomerBundle\Entity\PostalAddress", inversedBy="customer", cascade={"all"}, orphanRemoval=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_customers", "admin_export_corporationgroup", "admin_export_corporationsite"})
     *
     *
     * @GRID\Column(field="postalAddress.streetNumber", title="address_street_number", align="center", type="number", visible=true, groups={"general"})
     * @GRID\Column(field="postalAddress.streetNumber", title="address_street_number", align="center", type="number", visible=false, groups={"merged_address"})
     *
     * @GRID\Column(field="postalAddress.streetName", title="address_street_name", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=true, groups={"general"})
     * @GRID\Column(field="postalAddress.streetName", title="address_street_name", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=false)
     *
     * @GRID\Column(field="postalAddress.complement", title="address_complement", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=true,groups={"general"})
     * @GRID\Column(field="postalAddress.complement", title="address_complement", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=false)
     *
     * @GRID\Column(field="postalAddress.postalCode", title="address_postal_code", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=true, groups={"general"})
     * @GRID\Column(field="postalAddress.postalCode", title="address_postal_code", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=false, groups={"merged_address"})
     *
     * @GRID\Column(field="postalAddress.city", title="address_postal_city", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=true, groups={"general"})
     * @GRID\Column(field="postalAddress.city", title="address_postal_city", align="center", type="text", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=false, groups={"merged_address"})
     *
     * @GRID\Column(field="postalAddress.country", title="address_country", align="center", type="country", operators={"like", "nlike", "rslike", "llike"}, align="center", visible=true, groups={"default", "general", "merged_address"})
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

    abstract public function getTypeName();

    public static function getClassNameByCustomerType(string $customerType){
        if($customerType == Customer::TYPE_CORPO_GROUP){
            $className = "CustomerBundle:CorporationGroup";;
        }elseif($customerType == Customer::TYPE_CORPO_SITE){
            $className = "CustomerBundle:CorporationSite";
        }elseif($customerType == Customer::TYPE_PRIVATE_INDIVIDUAL){
            $className = "CustomerBundle:PrivateIndividual";
        }elseif($customerType == Customer::TYPE_TOWN_SHIP){
            $className = "CustomerBundle:TownShip";
        }elseif($customerType == Customer::TYPE_OTHER_CUSTOMER){
            $className = "CustomerBundle:OtherCustomer";
        }else{
            $className = "CustomerBundle\Entity\AbstractClass\Customer";
        }
        return $className;
    }
}