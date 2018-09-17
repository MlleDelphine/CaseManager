<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 14/09/2018
 * Time: 18:16
 */

namespace CustomerBundle\Entity\AbstractClass;

use CustomerBundle\Entity\PostalAddress;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMSSer;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"corporationGroup" = "CustomerBundle\Entity\CorporationGroup",
 *     "corporationSite" = "CustomerBundle\Entity\CorporationSite",
 *     "privateIndividual" = "CustomerBundle\Entity\PrivateIndividual"
 *     })
 */
abstract class Customer extends Person{

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

//    /**
//     * @var string
//     *
//     */
//    protected $slug;

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
}