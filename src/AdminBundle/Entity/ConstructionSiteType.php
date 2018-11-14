<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 18/05/2018
 * Time: 13:29
 */

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * ConstructionSiteType : PrestationDomain
 *
 * @ORM\Table(name="construction_site_type")
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\ConstructionSiteTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class ConstructionSiteType {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionSiteType"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_constructionSiteType"})
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
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
     * @var UnitTimePrice
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\UnitTimePrice", mappedBy="constructionSiteTypes", fetch="EXTRA_LAZY")
     *
     */
    protected $unitTimePrices;

    public function __construct()
    {
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
     * @return ConstructionSiteType
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
     * Set description
     *
     * @param string $description
     *
     * @return ConstructionSiteType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return ConstructionSiteType
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
     * @return ConstructionSiteType
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
     * Set slug
     *
     * @param string $slug
     *
     * @return ConstructionSiteType
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
     * Add unitTimePrice
     *
     * @param \AppBundle\Entity\UnitTimePrice $unitTimePrice
     *
     * @return ConstructionSiteType
     */
    public function addUnitTimePrice(\AppBundle\Entity\UnitTimePrice $unitTimePrice)
    {
        $this->unitTimePrices[] = $unitTimePrice;

        return $this;
    }

    /**
     * Remove unitTimePrice
     *
     * @param \AppBundle\Entity\UnitTimePrice $unitTimePrice
     */
    public function removeUnitTimePrice(\AppBundle\Entity\UnitTimePrice $unitTimePrice)
    {
        $this->unitTimePrices->removeElement($unitTimePrice);
    }

    /**
     * Get unitTimePrices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnitTimePrices()
    {
        return $this->unitTimePrices;
    }
}