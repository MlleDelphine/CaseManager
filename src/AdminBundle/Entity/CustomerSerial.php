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
 * Class CustomerSerial
 * @package AdminBundle\Entity
 *
 * @ORM\Table(name="customer_serial")
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\Repository\CustomerSerialRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class CustomerSerial
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
     * Inverse side
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\CustomerChapter", mappedBy="customerSerial", cascade={"persist", "merge", "remove"}, fetch="EXTRA_LAZY")
     *
     */
    protected $customerChapters;


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
     * @return CustomerSerial
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
     * @return CustomerSerial
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
     * @return CustomerSerial
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
     * @return CustomerSerial
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
     * Add customerChapter
     *
     * @param CustomerChapter $customerChapter
     *
     * @return CustomerSerial
     */
    public function addCustomerChapter(CustomerChapter $customerChapter)
    {
        $this->customerChapters[] = $customerChapter;
        $customerChapter->setCustomerSerial($this);

        return $this;
    }

    /**
     * Remove customerChapter
     *
     * @param CustomerChapter $customerChapter
     */
    public function removeCustomerChapter(CustomerChapter $customerChapter)
    {
        $this->customerChapters->removeElement($customerChapter);
    }

    /**
     * Get customerChapters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerChapters()
    {
        return $this->customerChapters;
    }
}