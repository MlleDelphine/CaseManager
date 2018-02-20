<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * TimePrice
 *
 * @ORM\Table(name="time_price")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimePriceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
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
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="unitaryPrice", type="decimal", precision=10, scale=2)
     * @Assert\Currency()
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_resource"})
     */
    private $unitaryPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromDate", type="datetime")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Date()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_resource"})
     */
    private $fromDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="untilDate", type="datetime", nullable=true)
     *
     * @Assert\IsNull()
     * @Assert\Date()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_material", "admin_export_resource"})
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
     * @var Material
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Material", inversedBy="timePrices", cascade={"persist", "merge"})
     * @Assert\NotBlank()
     */
    protected $material;

    /**
     * @var Resource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Resource", inversedBy="timePrices", cascade={"persist", "merge"})
     * @Assert\NotBlank()
     */
    protected $resource;

    public function __construct()
    {
        $now = new \DateTime();
        $this->untilDate = $now->add(new \DateInterval("P2Y"));
//        $this->constructionSites = new ArrayCollection();
    }

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $start = $this->getFromDate();
        $end = $this->getUntilDate();

        if ($end < $start) {
            $context
                ->buildViolation('La date de fin doit être postérieure à la date de départ.')
                ->atPath('untilDate')
                ->addViolation();
        }
        dump("Price");
        die;

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
     * Set resource
     *
     * @param \AppBundle\Entity\Resource $resource
     *
     * @return TimePrice
     */
    public function setResource(\AppBundle\Entity\Resource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return \AppBundle\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
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

