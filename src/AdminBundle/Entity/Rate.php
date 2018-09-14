<?php

namespace AdminBundle\Entity;

use AppBundle\Entity\Equipment;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * Rate
 *
 * @ORM\Table(name="rate", uniqueConstraints={@ORM\UniqueConstraint(name="no_duplication_name", columns={"name"})})
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\RateRepository")
 * @UniqueEntity(fields={"name"})
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class Rate
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_rate"})
     */
    private $name;

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
     * @ORM\Column(name="percentage", type="decimal", precision=10, scale=2)
     *
     * @Assert\Type(type="float", message="{{value}} n'est pas saisi sous un format de prix valide.")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_rate"})
     */
    private $percentage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromDate", type="datetime")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Date()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_rate"})
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
     * @JMSSer\Groups({"admin_export_rate"})
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


    public function __construct()
    {
        $now = new \DateTime();
        $this->untilDate = $now->add(new \DateInterval("P2Y"));
    }

    public function __toString() {
        return (string) $this->getName();
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
     * @return Rate
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Rate
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
     * Set percentage
     *
     * @param string $percentage
     *
     * @return Rate
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Get percentage
     *
     * @return string
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return Rate
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
     * @return Rate
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
     * @return Rate
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
     * @return Rate
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

