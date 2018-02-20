<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * Material
 *
 * @ORM\Table(name="material")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\MaterialRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class Material
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string")
     * @Assert\NotNull()
     */
    private $unit;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TimePrice", mappedBy="material", fetch="EXTRA_LAZY", cascade={"persist", "merge", "remove"})
     * @Assert\Count(min=1, minMessage="Vous devez renseigner au moins une plage de dates")
     *
     */
    protected $timePrices;

//    /**
//     * @var ConstructionSite[]|ArrayCollection
//     *
//     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ConstructionSite", mappedBy="jobStatus", fetch="EXTRA_LAZY")
//     */
//    protected $constructionSites;

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

//    /**
//     * Add constructionSite
//     *
//     * @param \AppBundle\Entity\ConstructionSite $constructionSite
//     *
//     * @return Material
//     */
//    public function addConstructionSite(\AppBundle\Entity\ConstructionSite $constructionSite)
//    {
//        $this->constructionSites[] = $constructionSite;
//
//        return $this;
//    }
//
//    /**
//     * Remove constructionSite
//     *
//     * @param \AppBundle\Entity\ConstructionSite $constructionSite
//     */
//    public function removeConstructionSite(\AppBundle\Entity\ConstructionSite $constructionSite)
//    {
//        $this->constructionSites->removeElement($constructionSite);
//    }
//
//    /**
//     * Get constructionSites
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getConstructionSites()
//    {
//        return $this->constructionSites;
//    }
}
