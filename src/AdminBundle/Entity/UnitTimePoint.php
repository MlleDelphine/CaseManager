<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * UnitTimePoint
 *
 * @ORM\Table(name="unit_time_point", uniqueConstraints={@ORM\UniqueConstraint(name="no_duplication_unit_point_article", columns={"from_date", "until_date", "unit", "customer_article_id"})})
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\UnitTimePointRepository")
 * @UniqueEntity(fields={"fromDate", "untilDate", "unitTimePoint", "customerArticle"})
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class UnitTimePoint
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
     * @ORM\Column(name="unit", type="string")
     * @Assert\NotNull()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_unit_time_point", "admin_export_unit_time_point"})
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="unitary_point", type="integer")
     *
     * @Assert\Type(type="number", message="{{value}} n'est pas saisi sous un format de prix valide.")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_unit_time_point", "admin_export_unit_time_point"})
     */
    private $unitaryPoint;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="from_date", type="datetime")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Date()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_unit_time_point"})
     */
    private $fromDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="until_date", type="datetime", nullable=true)
     *
     * @Assert\Date()
     * @Assert\GreaterThan(propertyPath="from_date", message="Oops, la date de fin doit être postérieure à la date de départ.")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_unit_time_point"})
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
     * Owning side
     * @var CustomerArticle
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\CustomerArticle", inversedBy="unitTimePoints")
     * @ORM\JoinColumn(name="customer_article_id", referencedColumnName="id", nullable=false)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_unit_time_point"})
     *
     */
    protected $customerArticle;

    public function __construct()
    {
        $now = new \DateTime();
        $this->untilDate = $now->add(new \DateInterval("P2Y"));
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
     * Set unit
     *
     * @param integer $unit
     *
     * @return UnitTimePoint
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
     * Set unitaryPoint
     *
     * @param string $unitaryPoint
     *
     * @return UnitTimePoint
     */
    public function setUnitaryPoint($unitaryPoint)
    {
        $this->unitaryPoint = $unitaryPoint;

        return $this;
    }

    /**
     * Get unitaryPoint
     *
     * @return string
     */
    public function getUnitaryPoint()
    {
        return $this->unitaryPoint;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return UnitTimePoint
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
     * @return UnitTimePoint
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return UnitTimePoint
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
     * @return UnitTimePoint
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
     * Set customerArticle
     *
     * @param CustomerArticle|null $customerArticle
     * @return UnitTimePoint
     */
    public function setCustomerArticle(CustomerArticle $customerArticle = null)
    {
        $this->customerArticle = $customerArticle;

        return $this;
    }

    /**
     * Get customerArticle
     *
     * @return CustomerArticle
     */
    public function getCustomerArticle()
    {
        return $this->customerArticle;
    }

}

