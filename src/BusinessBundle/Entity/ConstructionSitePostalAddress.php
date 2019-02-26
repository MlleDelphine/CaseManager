<?php
/**
 * Created by PhpStorm.
 * User: DG713C7N
 * Date: 26/02/2019
 * Time: 11:23
 */

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * ConstructionSitePostalAddress
 *
 * @ORM\Table(name="construction_site_postal_address")
 * @ORM\Entity(repositoryClass="BusinessBundle\Entity\Repository\ConstructionSitePostalAddressRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class ConstructionSitePostalAddress
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionsitepostaladdress"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="streetNumber", type="string", length=10, nullable=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionsitepostaladdress", "admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $streetNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="streetName", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionsitepostaladdress", "admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $streetName;

    /**
     * @var string
     *
     * @ORM\Column(name="complement", type="string", length=255, nullable=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionsitepostaladdress", "admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $complement;

    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", length=10)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionsitepostaladdress", "admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionsitepostaladdress", "admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionsitepostaladdress", "admin_export_corporationgroup", "admin_export_corporationsite"})
     */
    protected $country;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"id", "city"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
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
     * @ORM\OneToOne(targetEntity="BusinessBundle\Entity\BusinessCase", mappedBy="constructionSitePostalAddress")
     *
     */
    public $businessCase;

    public function __construct()
    {
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
     * Set streetNumber
     *
     * @param string $streetNumber
     *
     * @return ConstructionSitePostalAddress
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set streetName
     *
     * @param string $streetName
     *
     * @return ConstructionSitePostalAddress
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * Get streetName
     *
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetName;
    }


    /**
     * Set complement
     *
     * @param string $complement
     *
     * @return ConstructionSitePostalAddress
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return ConstructionSitePostalAddress
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return ConstructionSitePostalAddress
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return ConstructionSitePostalAddress
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return ConstructionSitePostalAddress
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
     * @return ConstructionSitePostalAddress
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        if($updated > $this->getBusinessCase()->getUpdated())
        {
            $this->getBusinessCase()->setUpdated($updated);
        }

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
     * Set slug
     *
     * @param string $slug
     *
     * @return ConstructionSitePostalAddress
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
     * Set corporationGroup
     *
     * @param BusinessCase businessCase
     *
     * @return ConstructionSitePostalAddress
     */
    public function setBusinessCase(BusinessCase $businessCase = null)
    {
        $this->businessCase = $businessCase;

        return $this;
    }

    /**
     * Get BusinessCase
     *
     * @return BusinessCase
     */
    public function getBusinessCase()
    {
        return $this->businessCase;
    }
}