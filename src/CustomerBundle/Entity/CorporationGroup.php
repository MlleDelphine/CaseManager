<?php

namespace CustomerBundle\Entity;

use CustomerBundle\Entity\AbstractClass\Corporation;
use CustomerBundle\Entity\AbstractClass\CustomerSubjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSer;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * CorporationGroup
 *
 *
 * @ORM\Entity(repositoryClass="CustomerBundle\Entity\Repository\CorporationGroupRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @JMSSer\ExclusionPolicy("all")
 *
 *
 * @GRID\Source(columns="id, slug, name, legalStatus, concatenated_postal_address, postalAddress.country, postalAddress.streetNumber, postalAddress.streetName, postalAddress.complement, postalAddress.postalCode, created, updated")
 * @GRID\Column(id="concatenated_postal_address", type="text", title="postal_address", field="CONCAT(postalAddress.streetNumber, ', ', postalAddress.streetName, ' ', postalAddress.postalCode, ' ', postalAddress.city)", operators={"like"}, isManualField=true, source=true)
 */
class CorporationGroup extends Corporation implements CustomerSubjectInterface
{
    //postalAddress.streetNumber, postalAddress.streetName, postalAddress.complement, postalAddress.postalCode, postalAddress.country

    const ObjectName = "CorporationGroup";
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationgroup", "admin_export_corporationsite"})
     * @GRID\Column(title="name", operators={"like", "nlike", "rslike", "llike" }, type="text", visible=true, align="left", class="column-title", groups={"general", "merged_address"})
     *
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="legalStatus", type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMSSer\Expose()
     * @JMSSer\Groups({"admin_export_corporationgroup", "admin_export_corporationsite"})
     *
     * @GRID\Column(title="legal_status_capitalize", operators={"like"}, defaultOperator="like", type="text", visible=true, align="left", filter="select", selectMulti=true, selectFrom="source", values={""="Tous", "EI"="corporation_legal_status_ei", "SARL"="corporation_legal_status_sarl", "SAS"="corporation_legal_status_sas" })
     */
    protected $legalStatus;

    /**
     * @var CorporationSite[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerBundle\Entity\CorporationSite", mappedBy="corporationGroup", fetch="EXTRA_LAZY", cascade={"persist", "detach"})
     */
    protected $corporationSites;

    public function __construct()
    {
        $this->corporationSites = new ArrayCollection();
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
     * @return CorporationGroup
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
     * @return CorporationGroup
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
     * Set legalStatus
     *
     * @param string $legalStatus
     *
     * @return CorporationGroup
     */
    public function setLegalStatus($legalStatus)
    {
        $this->legalStatus = $legalStatus;

        return $this;
    }

    /**
     * Get legalStatus
     *
     * @return string
     */
    public function getLegalStatus()
    {
        return $this->legalStatus;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CorporationGroup
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
     * @return CorporationGroup
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
     * Add corporationSite
     *
     * @param CorporationSite $corporationSite
     *
     * @return CorporationGroup
     */
    public function addCorporationSite(CorporationSite $corporationSite)
    {
        $this->corporationSites[] = $corporationSite;

        return $this;
    }

    /**
     * Remove corporationSite
     *
     * @param CorporationSite $corporationSite
     */
    public function removeCorporationSite(CorporationSite $corporationSite)
    {
        $this->corporationSites->removeElement($corporationSite);
    }

    /**
     * Get corporationSites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporationSites()
    {
        return $this->corporationSites;
    }

    public function getObjectName() {
        return self::ObjectName;
    }

    public function getHtmlName() {
        return $this->__toString()." (groupe)";
    }

    public function getType() {
        return parent::TYPE_CORPO_GROUP;
    }

    public function getTypeName(){
        return "corpo_groups_capitalize";
    }
}
