<?php
// src/AppBundle/Entity/User.php

namespace SecurityAppBundle\Entity;

use AppBundle\Entity\JobStatus;
use AppBundle\Entity\ResourceSubjectInterface;
use AppBundle\Entity\Team;
use AppBundle\Entity\TimePrice;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 * @GRID\Source(columns="id, slug, username, joined_full_name, lastName, firstName, email, jobStatus.name, team.name, enabled, created, updated", groups={"default", "general"})
 * @GRID\Column(id="joined_full_name", type="join", title="full_name_capitalize", operators={"like"}, columns={"lastName", "firstName"}, source="true", groups={"default", "general"})
 *
 */
class User extends BaseUser implements ResourceSubjectInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(title="ID", operators={"eq", "neq", "gt", "lt", "gte", "lte"}, defaultOperator="eq", type="number", visible=false, align="left")
     *
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(title="firstname_capitalize", operators={"like", "nlike"}, defaultOperator="like", visible=true, align="center", groups={"default"})
     * @GRID\Column(title="firstname_capitalize", operators={"like", "nlike"}, defaultOperator="like", visible=false, align="center", groups={"general"})
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(title="lastname_capitalize", operators={"like", "nlike"}, defaultOperator="like", visible=true, align="center", groups={"default"})
     * @GRID\Column(title="lastname_capitalize", operators={"like", "nlike"}, defaultOperator="like", visible=false, align="center", groups={"general"})
     */
    protected $lastName;

    /**
     * @Assert\NotBlank()
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(title="nickname_capitalize", operators={"like", "nlike"}, defaultOperator="like", visible=true, align="center", groups={"default", "general"})
     */
    protected $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(title="email_capitalize", operators={"like", "nlike"}, defaultOperator="like", visible=true, align="center", groups={"default", "general"})
     */
    protected $email;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName", "lastName"}, separator="-", updatable=true, unique=true)
     * @ORM\Column(length=128, unique=true)
     *
     * @GRID\Column(title="Slug", type="text", visible=false, groups={"default", "general"})
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Regex("/^((\+\d{2})|0)[0-9]{9}$/")
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     */
    protected $phoneNumber;

    /**
     * @var bool
     * @GRID\Column(title="status_capitalize", type="boolean", visible=true, groups={"default", "general"})
     */
    protected $enabled;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     *
     * @GRID\Column(title="creation", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")
     *
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     *
     * @GRID\Column(title="updated_f_s", operators={"eq", "neq", "gt", "lt", "gte", "lte", "btw", "btwe"}, defaultOperator="eq", type="datetime", format="d-m-Y H:i:s", visible=true, align="center")
     *
     */
    protected $updated;

    /**
     * @var JobStatus
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\JobStatus", inversedBy="users", cascade={"persist", "merge"})
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(field="jobStatus.name", title="job_capitalize", visible="true", groups={"general", "default"})
     *
     */
    protected $jobStatus;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", inversedBy="users")
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     *
     * @GRID\Column(field="team.name", title="team_capitalize", visible="true", groups={"general", "default"})
     *
     */
    protected $team;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string")
     * @Assert\NotNull()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     */
    private $unit;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TimePrice", mappedBy="userEmployee", fetch="EXTRA_LAZY", cascade={"persist", "merge"}, orphanRemoval=false)
     *
     * @Assert\Count(min=1, minMessage="Vous devez renseigner au moins une plage de dates")
     * @Assert\All(
     *      @Assert\Type(
     *          type="AppBundle\Entity\TimePrice"
     *      )
     * )
     * @Assert\Valid()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     */
    protected $timePrices;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="BusinessBundle\Entity\BusinessCase", mappedBy="user", fetch="EXTRA_LAZY", cascade={"persist", "merge"}, orphanRemoval=false)
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_user"})
     */
    protected $businessCases;


    public function __construct()
    {
        parent::__construct();
        $this->addRole("ROLE_USER");
        $this->timePrices = new ArrayCollection();
        // your own logic
    }

    public function __toString()
    {
        return $this->firstName." ".$this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return User
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
     * Set phoneNumber
     *
     * @param integer $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return integer
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
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
     * @return User
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
     * Set jobStatus
     *
     * @param JobStatus $jobStatus
     *
     * @return User
     */
    public function setJobStatus(JobStatus $jobStatus = null)
    {
        $this->jobStatus = $jobStatus;

        return $this;
    }

    /**
     * Get jobStatus
     *
     * @return JobStatus
     */
    public function getJobStatus()
    {
        return $this->jobStatus;
    }

    /**
     * Set team
     *
     * @param Team $team
     *
     * @return User
     */
    public function setTeam(Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set unit
     *
     * @param integer $unit
     *
     * @return User
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
     * Add timePrice
     *
     * @param TimePrice $timePrice
     *
     * @return User
     */
    public function addTimePrice(TimePrice $timePrice)
    {
        $this->timePrices[] = $timePrice;
        $timePrice->setUserEmployee($this);

        return $this;
    }

    /**
     * Remove timePrice
     *
     * @param TimePrice $timePrice
     */
    public function removeTimePrice(TimePrice $timePrice)
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

    public function getObjectName() {
        return "internal_employee";
    }
}
