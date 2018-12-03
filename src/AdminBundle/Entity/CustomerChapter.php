<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 30/11/2018
 * Time: 12:06
 */

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * Class CustomerChapter
 * @package AdminBundle\Entity
 *
 * @ORM\Table(name="customer_chapter")
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\CustomerChapterRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class CustomerChapter
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
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_serial"})
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
     * Owning side
     * @var CustomerSerial
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\CustomerSerial", inversedBy="customerChapters")
     * @ORM\JoinColumn(name="customer_serial_id", referencedColumnName="id", nullable=false)
     */
    protected $customerSerial;

    /**
     * Inverse side
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\CustomerArticle", mappedBy="customerChapter", cascade={"persist", "merge", "remove"}, fetch="EXTRA_LAZY")
     *
     */
    protected $customerArticles;



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
     * @return CustomerChapter
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
     * @return CustomerChapter
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CustomerChapter
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
     * @return CustomerChapter
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
     * Set customerSerial
     *
     * @param CustomerSerial $customerSerial
     *
     * @return CustomerChapter
     */
    public function setCustomerSerial(CustomerSerial $customerSerial = null)
    {
        $this->customerSerial = $customerSerial;

        return $this;
    }

    /**
     * Get customerSerial
     *
     * @return CustomerSerial
     */
    public function getCustomerSerial()
    {
        return $this->customerSerial;
    }

    /**
     * Add customerChapter
     *
     * @param CustomerArticle $customerArticle
     *
     * @return CustomerChapter
     */
    public function addCustomerArticle(CustomerArticle $customerArticle)
    {
        $this->customerArticles[] = $customerArticle;
        $customerArticle->setCustomerChapter($this);

        return $this;
    }

    /**
     * Remove customerArticle
     *
     * @param CustomerArticle $customerArticle
     */
    public function removeCustomerArticle(CustomerArticle $customerArticle)
    {
        $this->customerArticles->removeElement($customerArticle);
    }

    /**
     * Get customerArticles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerArticles()
    {
        return $this->customerArticles;
    }

}