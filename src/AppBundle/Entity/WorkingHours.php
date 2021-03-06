<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Institution;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WorkingHours
 */
class WorkingHours
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

     /**
     * @ORM\ManyToOne(targetEntity="Institution", inversedBy="workingTime")
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id")
     */
    private $institution;

    /**
     * @ORM\OneToMany(targetEntity="WorkingDays", mappedBy="workingHours")
     */
    private $workingDays;

    /**
     * @var boolean
     */
    private $isDefaultSchedule;

    public function __construct()
    {
        $this->workingDays = new ArrayCollection();
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
     * @return WorkingHours
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
     * Add workingDay
     *
     * @param \AppBundle\Entity\WorkingDays $workingDay
     *
     * @return WorkingHours
     */
    public function addWorkingDay(\AppBundle\Entity\WorkingDays $workingDay)
    {
        $this->workingDays[] = $workingDay;

        return $this;
    }

    /**
     * Remove workingDay
     *
     * @param \AppBundle\Entity\WorkingDays $workingDay
     */
    public function removeWorkingDay(\AppBundle\Entity\WorkingDays $workingDay)
    {
        $this->workingDays->removeElement($workingDay);
    }

    /**
     * Get workingDays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkingDays()
    {
        return $this->workingDays;
    }

    /**
     * Set institution
     *
     * @param \AppBundle\Entity\Institution $institution
     *
     * @return WorkingHours
     */
    public function setInstitution(\AppBundle\Entity\Institution $institution = null)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return \AppBundle\Entity\Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }
    /**
     * @var integer
     */
    private $institutionId;


    /**
     * Set institutionId
     *
     * @param integer $institutionId
     *
     * @return WorkingHours
     */
    public function setInstitutionId($institutionId)
    {
        $this->institutionId = $institutionId;

        return $this;
    }

    /**
     * Get institutionId
     *
     * @return integer
     */
    public function getInstitutionId()
    {
        return $this->institutionId;
    }

    /**
     * Set isDefaultSchedule
     *
     * @param boolean $isDefaultSchedule
     *
     * @return WorkingHours
     */
    public function setIsDefaultSchedule($isDefaultSchedule)
    {
        $this->isDefaultSchedule = $isDefaultSchedule;

        return $this;
    }

    /**
     * Get isDefaultSchedule
     *
     * @return boolean
     */
    public function getIsDefaultSchedule()
    {
        return $this->isDefaultSchedule;
    }
}
