<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 18/05/2018
 * Time: 13:29
 */

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * CustomerArticle
 *
 * @ORM\Table(name="customer_article")
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\CustomerArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 * @GRID\Source(columns="id, slug, name, designation, reference, color")
 *
 */
class CustomerArticle {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @GRID\Column(title="ID", operators={"eq", "neq", "gt", "lt", "gte", "lte"}, defaultOperator="eq", type="number", visible=false, align="left")
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
     * @JMSSer\Groups({"admin_export_customerarticle"})
     *
     * @GRID\Column(title="name", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="text", nullable=true)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_customerarticle"})
     *
     * @GRID\Column(title="designation", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title")
     */
    private $designation;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=8, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"business_export_business_work_site"})
     *
     * @GRID\Column(title="color", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title")
     */
    private $color;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     * @GRID\Column(title="Slug", type="text", visible=false)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=false, unique=true)
     *
     * @GRID\Column(title="reference", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title")
     */
    private $reference;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     *
     * @GRID\Column(title="created_m_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     *
     * @GRID\Column(title="updated_f_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")
     */
    protected $updated;

    /**
     * Inverse side
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\UnitTimePoint", mappedBy="customerArticle", cascade={"persist", "merge", "remove"}, fetch="EXTRA_LAZY")
     *
     */
    protected $unitTimePoints;

    /**
     * Owning side
     * @var CustomerChapter
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\CustomerChapter", inversedBy="customerArticles")
     * @ORM\JoinColumn(name="customer_chapter_id", referencedColumnName="id", nullable=false)
     */
    protected $customerChapter;

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
     * @return CustomerArticle
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
     * Set designation
     *
     * @param string $designation
     *
     * @return CustomerArticle
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @param string $color
     * @return CustomerArticle
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
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return CustomerArticle
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CustomerArticle
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
     * @return CustomerArticle
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
     * @return CustomerArticle
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
     * Add unitTimePoint
     *
     * @param UnitTimePoint $unitTimePoint
     *
     * @return CustomerArticle
     */
    public function addUnitTimePoint(UnitTimePoint $unitTimePoint)
    {
        $this->unitTimePoints[] = $unitTimePoint;
        $unitTimePoint->setCustomerArticle($this);

        return $this;
    }

    /**
     * Remove unitTimePoint
     *
     * @param UnitTimePoint $unitTimePoint
     */
    public function removeUnitTimePoint(UnitTimePoint $unitTimePoint)
    {
        $this->unitTimePoints->removeElement($unitTimePoint);
    }

    /**
     * Get unitTimePoints
     *
     * @return Collection
     */
    public function getUnitTimePoints()
    {
        return $this->unitTimePoints;
    }

    /**
     * Set customerChapter
     *
     * @param CustomerChapter $customerChapter
     *
     * @return CustomerArticle
     */
    public function setCustomerChapter(CustomerChapter $customerChapter = null)
    {
        $this->customerChapter = $customerChapter;

        return $this;
    }

    /**
     * Get customerChapter
     *
     * @return CustomerChapter
     */
    public function getCustomerChapter()
    {
        return $this->customerChapter;
    }

}