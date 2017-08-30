<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * WorkingDays
 */
class WorkingDays
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $dayNumber;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="WorkingHours", inversedBy="workingDays")
     * @ORM\JoinColumn(name="working_hours_id", referencedColumnName="id")
     */
    private $workingHours;


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
     * Set dayNumber
     *
     * @param integer $dayNumber
     *
     * @return WorkingDays
     */
    public function setDayNumber($dayNumber)
    {
        $this->dayNumber = $dayNumber;

        return $this;
    }

    /**
     * Get dayNumber
     *
     * @return int
     */
    public function getDayNumber()
    {
        return $this->dayNumber;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return WorkingDays
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return WorkingDays
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set workingHours
     *
     * @param \AppBundle\Entity\WorkingHours $workingHours
     *
     * @return WorkingDays
     */
    public function setWorkingHours(\AppBundle\Entity\WorkingHours $workingHours = null)
    {
        $this->workingHours = $workingHours;

        return $this;
    }

    /**
     * Get workingHours
     *
     * @return \AppBundle\Entity\WorkingHours
     */
    public function getWorkingHours()
    {
        return $this->workingHours;
    }
}
