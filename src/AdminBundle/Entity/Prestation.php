<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 18/05/2018
 * Time: 13:29
 */

namespace AdminBundle\Entity;

use AppBundle\Entity\UnitTimePrice;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * Prestation : PrestationDomain
 *
 * @ORM\Table(name="prestation")
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\PrestationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class Prestation {

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
     * @JMSSer\Groups({"admin_export_prestation"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_prestation"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=8, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_work_site"})
     */
    private $color;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="internalReference", type="string", length=255, nullable=true, unique=true)
     */
    private $internalReference;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UnitTimePrice", mappedBy="prestation", cascade={"persist", "merge", "remove"}, fetch="EXTRA_LAZY")
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
     * @return Prestation
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
     * @return Prestation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $color
     * @return Prestation
     */
    public function setColor(string $color) {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
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
     * Set internalReference
     *
     * @param string $internalReference
     *
     * @return Prestation
     */
    public function setInternalReference($internalReference)
    {
        $this->internalReference = $internalReference;

        return $this;
    }

    /**
     * Get internalReference
     *
     * @return string
     */
    public function getInternalReference()
    {
        return $this->internalReference;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Prestation
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
     * @return Prestation
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
     * @return Prestation
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
     * @param UnitTimePrice $unitTimePrice
     *
     * @return Prestation
     */
    public function addUnitTimePrice(UnitTimePrice $unitTimePrice)
    {
        $this->unitTimePrices[] = $unitTimePrice;
        $unitTimePrice->setWorksSiteType($this);

        return $this;
    }

    /**
     * Remove unitTimePrice
     *
     * @param UnitTimePrice $unitTimePrice
     */
    public function removeUnitTimePrice(UnitTimePrice $unitTimePrice)
    {
        $this->unitTimePrices->removeElement($unitTimePrice);
    }

    /**
     * Get unitTimePrices
     *
     * @return Collection
     */
    public function getUnitTimePrices()
    {
        return $this->unitTimePrices;
    }
}