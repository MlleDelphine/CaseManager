<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Material
 *
 * @ORM\Table(name="material")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\MaterialRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 * @@GRID\Source(columns="id, slug, name, reference, unit, created, updated", groups={"general"})
 *
 */
class Material implements ResourceSubjectInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_timeprice"})
     *
     * @GRID\Column(title="ID", operators={"eq", "neq", "gt", "lt", "gte", "lte"}, defaultOperator="eq", type="number", visible=false, align="left", groups={"default", "general"})
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_timeprice"})
     *
     * @GRID\Column(title="name", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     *
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_timeprice"})
     *
     * @GRID\Column(title="reference", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     *
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_timeprice"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string")
     * @Assert\NotNull()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_timeprice"})
     *
     * @GRID\Column(title="unit", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     *
     */
    private $unit;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     * @GRID\Column(title="Slug", type="text", visible=false, groups={"default", "general"})
     *
     */
    protected $slug;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     *
     * @GRID\Column(title="creation", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center", groups={"default", "general"})
     *
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     *
     * @GRID\Column(title="updated_f_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center", groups={"default", "general"})
     *
     */
    protected $updated;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TimePrice", mappedBy="material", fetch="EXTRA_LAZY", cascade={"persist", "merge", "remove"})
     *   * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material"})
     *
     * @Assert\Count(min=1, minMessage="Vous devez renseigner au moins une plage de dates")
     * @Assert\All(
     *      @Assert\Type(
     *          type="AppBundle\Entity\TimePrice"
     *      )
     * )
     * @Assert\Valid()
     */

    protected $timePrices;


    public function __construct()
    {
        $this->timePrices = new ArrayCollection();
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
     * @return Material
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
     * Set reference
     *
     * @param string $reference
     *
     * @return Material
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Material
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
     * Set unit
     *
     * @param integer $unit
     *
     * @return Material
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return integer
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Material
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
     * @return Material
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
     * @return Material
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
     * Add timePrice
     *
     * @param \AppBundle\Entity\TimePrice $timePrice
     *
     * @return Material
     */
    public function addTimePrice(\AppBundle\Entity\TimePrice $timePrice)
    {
        $this->timePrices[] = $timePrice;
        $timePrice->setMaterial($this);

        return $this;
    }

    /**
     * Remove timePrice
     *
     * @param \AppBundle\Entity\TimePrice $timePrice
     */
    public function removeTimePrice(\AppBundle\Entity\TimePrice $timePrice)
    {
        $this->timePrices->removeElement($timePrice);
    }

    /**
     * Get timePrices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTimePrices()
    {
        return $this->timePrices;
    }

    public function getObjectName() {
        return "material";
    }
}
