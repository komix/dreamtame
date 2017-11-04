<?php

namespace AppBundle\Entity;

/**
 * Area
 */
class Area
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
     * @var float
     */
    private $fromLat;

    /**
     * @var float
     */
    private $fromLng;

    /**
     * @var float
     */
    private $toLat;

    /**
     * @var float
     */
    private $toLng;

     /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="areas")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;


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
     * @return Area
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
     * Set fromLat
     *
     * @param float $fromLat
     *
     * @return Area
     */
    public function setFromLat($fromLat)
    {
        $this->fromLat = $fromLat;

        return $this;
    }

    /**
     * Get fromLat
     *
     * @return float
     */
    public function getFromLat()
    {
        return $this->fromLat;
    }

    /**
     * Set fromLng
     *
     * @param float $fromLng
     *
     * @return Area
     */
    public function setFromLng($fromLng)
    {
        $this->fromLng = $fromLng;

        return $this;
    }

    /**
     * Get fromLng
     *
     * @return float
     */
    public function getFromLng()
    {
        return $this->fromLng;
    }

    /**
     * Set toLat
     *
     * @param float $toLat
     *
     * @return Area
     */
    public function setToLat($toLat)
    {
        $this->toLat = $toLat;

        return $this;
    }

    /**
     * Get toLat
     *
     * @return float
     */
    public function getToLat()
    {
        return $this->toLat;
    }

    /**
     * Set toLng
     *
     * @param float $toLng
     *
     * @return Area
     */
    public function setToLng($toLng)
    {
        $this->toLng = $toLng;

        return $this;
    }

    /**
     * Get toLng
     *
     * @return float
     */
    public function getToLng()
    {
        return $this->toLng;
    }
}

