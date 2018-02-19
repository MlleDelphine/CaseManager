<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * TimePrice
 *
 * @ORM\Table(name="time_price")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimePriceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class TimePrice
{
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
     * @ORM\Column(name="unitaryPrice", type="decimal", precision=10, scale=2)
     * @Assert\Currency()
     */
    private $unitaryPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromDate", type="datetime")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Date()
     */
    private $fromDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="untilDate", type="datetime", nullable=true)
     */
    private $untilDate;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     *
     * @var Material
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Material", inversedBy="timePrices", cascade={"persist", "merge"})
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"bo_export_timePrice"})
     */
    protected $material;

    public function __construct()
    {
//        $this->constructionSites = new ArrayCollection();
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
     * Set unitaryPrice
     *
     * @param string $unitaryPrice
     *
     * @return TimePrice
     */
    public function setUnitaryPrice($unitaryPrice)
    {
        $this->unitaryPrice = $unitaryPrice;

        return $this;
    }

    /**
     * Get unitaryPrice
     *
     * @return string
     */
    public function getUnitaryPrice()
    {
        return $this->unitaryPrice;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return TimePrice
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set untilDate
     *
     * @param \DateTime $untilDate
     *
     * @return TimePrice
     */
    public function setUntilDate($untilDate)
    {
        $this->untilDate = $untilDate;

        return $this;
    }

    /**
     * Get untilDate
     *
     * @return \DateTime
     */
    public function getUntilDate()
    {
        return $this->untilDate;
    }

    /**
     * Set material
     *
     * @param \AppBundle\Entity\Material $material
     *
     * @return TimePrice
     */
    public function setMaterial(\AppBundle\Entity\Material $material = null)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material
     *
     * @return \AppBundle\Entity\Material
     */
    public function getMaterial()
    {
        return $this->material;
    }
    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return TimePrice
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
     * @return TimePrice
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
}

