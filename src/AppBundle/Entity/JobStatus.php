<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SecurityAppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * JobStatus
 *
 * @ORM\Table(name="job_status")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\JobStatusRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 * @GRID\Source(columns="id, slug, name, created, updated", groups={"general"})
 *
 */
class JobStatus
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(title="ID", operators={"eq", "neq", "gt", "lt", "gte", "lte"}, defaultOperator="eq", type="number", visible=false, align="left", groups={"default", "general"})
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
     * @JMSSer\Groups({"admin_export_user", "admin_export_jobstatus"})
     *
     * @GRID\Column(title="name", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general"})
     *
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     * @GRID\Column(title="Slug", type="text", visible=false, groups={"default", "general"})
     */
    protected $slug;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     *
     * @GRID\Column(title="creation", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center", groups={"default", "general"})
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     *
     * @GRID\Column(title="updated_f_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center", groups={"default", "general"})
     */
    protected $updated;

    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SecurityAppBundle\Entity\User", mappedBy="jobStatus", fetch="EXTRA_LAZY")
     *
     */
    protected $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return JobStatus
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
     * @return JobStatus
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
     * @return JobStatus
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
     * @return JobStatus
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
     * Add user
     *
     * @param User $user
     *
     * @return JobStatus
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        //$user->setJobStatus($this);

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
        $user->setJobStatus(null);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
