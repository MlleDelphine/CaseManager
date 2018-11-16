<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\CustomerContact;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;

/**
 * CorporationJobStatus
 *
 * @ORM\Table(name="corporation_job_status")
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CorporationJobStatusRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 */
class CorporationJobStatus
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationcontact"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationcontact", "admin_export_corporationjobstatus"})
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
     * @var CustomerContact[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CustomerContact", mappedBy="corporationJobStatus", fetch="EXTRA_LAZY")
     */
    protected $customerContacts;


    public function __construct()
    {
        $this->customerContacts = new ArrayCollection();
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
     * @return CorporationJobStatus
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CorporationJobStatus
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
     * @return CorporationJobStatus
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
     * @return CorporationJobStatus
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
     * Add contact
     *
     * @param CustomerContact $contact
     *
     * @return CorporationJobStatus
     */
    public function addCustomerContact(CustomerContact $contact)
    {
        $this->customerContacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param CustomerContact $contact
     */
    public function removeCustomerContact(CustomerContact $contact)
    {
        $this->customerContacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerContacts()
    {
        return $this->customerContacts;
    }
}
