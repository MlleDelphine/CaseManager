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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
 * @var string
 *
 * @ORM\Column(name="quantity", type="integer", options={"default" : 0})
 */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="unitary_price", type="integer", options={"default" : 0})
     */
    private $unitaryPrice;

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

//    /**
//     * @var User[]|ArrayCollection
//     *
//     * @ORM\OneToMany(targetEntity="SecurityAppBundle\Entity\User", mappedBy="jobStatus", fetch="EXTRA_LAZY")
//     */
//    protected $users;

    public function __construct()
    {
//        $this->users = new ArrayCollection();
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Material
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unitaryPrice
     *
     * @param integer $unitaryPrice
     *
     * @return Material
     */
    public function setUnitaryPrice($unitaryPrice)
    {
        $this->unitaryPrice = $unitaryPrice;

        return $this;
    }

    /**
     * Get unitaryPrice
     *
     * @return integer
     */
    public function getUnitaryPrice()
    {
        return $this->unitaryPrice;
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

//    /**
//     * Add user
//     *
//     * @param \SecurityAppBundle\Entity\User $user
//     *
//     * @return Material
//     */
//    public function addUser(\SecurityAppBundle\Entity\User $user)
//    {
//        $this->users[] = $user;
//
//        return $this;
//    }
//
//    /**
//     * Remove user
//     *
//     * @param \SecurityAppBundle\Entity\User $user
//     */
//    public function removeUser(\SecurityAppBundle\Entity\User $user)
//    {
//        $this->users->removeElement($user);
//    }
//
//    /**
//     * Get users
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getUsers()
//    {
//        return $this->users;
//    }
}
