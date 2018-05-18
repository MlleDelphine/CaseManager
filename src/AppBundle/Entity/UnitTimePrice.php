<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Equipment;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * UnitTimePrice
 *
 * @ORM\Table(name="unit_time_price", uniqueConstraints={@ORM\UniqueConstraint(name="no_duplication_unit_price_equipment", columns={"fromDate", "untilDate", "equipment_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnitTimePriceRepository")
 * @UniqueEntity(fields={"fromDate", "untilDate", "equipment_id"})
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class UnitTimePrice
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
     * @JMSSer\Groups({"admin_export_equipment", "admin_export_unittimeprice"})
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="unitaryPrice", type="decimal", precision=10, scale=2)
     *
     * @Assert\Type(type="float", message="{{value}} n'est pas saisi sous un format de prix valide.")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_equipment", "admin_export_equipment"})
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
     * @JMSSer\Groups({"admin_export_equipment", "admin_export_equipment"})
     */
    private $fromDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="untilDate", type="datetime", nullable=true)
     *
     * @Assert\Date()
     * @Assert\GreaterThan(propertyPath="fromDate", message="Oops, la date de fin doit être postérieure à la date de départ.")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_equipment"})
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
     * @var Equipment
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipment", inversedBy="unitTimePrices", cascade={"persist", "merge"})
     * @Assert\NotBlank()
     */
    protected $equipment;

    /**
     * @var ConstructionSiteType
     * @ORM\ManyToMany(targetEntity="AdminBundle\Entity\ConstructionSiteType", inversedBy="unitTimePrices", cascade={"persist", "merge"})
     * @Assert\NotBlank()
     * @Assert\Count(min=1, minMessage="Vous devez renseigner au moins un domaine d'application.")
     * @Assert\All(
     *      @Assert\Type(
     *          type="AdminBundle\Entity\ConstructionSiteType"
     *      )
     * )
     * @Assert\Valid()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_equipment"})
     *
     */
    protected $constructionSiteTypes;

    public function __construct()
    {
        $now = new \DateTime();
        $this->untilDate = $now->add(new \DateInterval("P2Y"));
    }

//    /**
//     * @Assert\Callback()
//     *
//     * @param ExecutionContextInterface $context
//     * @param $payload
//     */
//    public function validate(ExecutionContextInterface $context, $payload)
//    {
//        $start = $this->getFromDate();
//        $end = $this->getUntilDate();
//
//        if ($end < $start) {
//            $context
//                ->buildViolation('La date de fin doit être postérieure à la date de départ.')
//                ->atPath('untilDate')
//                ->addViolation();
//        }
//    }

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
     * @return UnitTimePrice
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
     * Set unitaryPrice
     *
     * @param string $unitaryPrice
     *
     * @return UnitTimePrice
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
     * @return UnitTimePrice
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
     * @return UnitTimePrice
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
     * @return UnitTimePrice
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
     * @return UnitTimePrice
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
     * Set equipment
     *
     * @param Equipment $equipment
     *
     * @return UnitTimePrice
     */
    public function setEquipment(Equipment $equipment = null)
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * Get equipment
     *
     * @return Equipment
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * Add constructionSiteType
     *
     * @param \AppBundle\Entity\ConstructionSiteType $constructionSiteType
     *
     * @return UnitTimePrice
     */
    public function addConstructionSiteType(\AdminBundle\Entity\ConstructionSiteType $constructionSiteType)
    {
        $this->constructionSiteTypes[] = $constructionSiteType;
        $constructionSiteType->addUnitTimePrice($this);

        return $this;
    }

    /**
     * Remove constructionSiteType
     *
     * @param \AppBundle\Entity\ConstructionSiteType $constructionSiteType
     */
    public function removeConstructionSiteType(\AdminBundle\Entity\ConstructionSiteType $constructionSiteType)
    {
        $this->constructionSiteTypes->removeElement($constructionSiteType);
    }

    /**
     * Get constructionSiteTypes
     *
     * @return \AppBundle\Entity\ConstructionSiteType
     */
    public function getConstructionSiteTypes()
    {
        return $this->constructionSiteTypes;
    }
}

