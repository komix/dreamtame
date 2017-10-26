<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Institution;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PhoneNumber
 */
class PhoneNumber
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $rawNumber;

     /**
     * @ORM\ManyToOne(targetEntity="Institution", inversedBy="phoneNumbers")
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id")
     */
    private $institution;


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
     * Set rawNumber
     *
     * @param string $rawNumber
     *
     * @return PhoneNumber
     */
    public function setRawNumber($rawNumber)
    {
        $this->rawNumber = $rawNumber;

        return $this;
    }

    /**
     * Get rawNumber
     *
     * @return string
     */
    public function getRawNumber()
    {
        return $this->rawNumber;
    }

    /**
     * Set institution
     *
     * @param \AppBundle\Entity\Institution $institution
     *
     * @return PhoneNumber
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
}
