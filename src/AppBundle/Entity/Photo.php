<?php

namespace AppBundle\Entity;

/**
 * Photo
 */
class Photo
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $src;

    /**
     * @var string
     */
    private $msrc;

    /**
     * @var string
     */
    private $w;

    /**
     * @var int
     */
    private $h;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var int
     */
    private $instId;

    /**
     * @var int
     */
    private $usrId;

    /**
     * @var string
     */
    private $sqr;


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
     * Set src
     *
     * @param string $src
     *
     * @return Photo
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set msrc
     *
     * @param string $msrc
     *
     * @return Photo
     */
    public function setMsrc($msrc)
    {
        $this->msrc = $msrc;

        return $this;
    }

    /**
     * Get msrc
     *
     * @return string
     */
    public function getMsrc()
    {
        return $this->msrc;
    }

    /**
     * Set w
     *
     * @param string $w
     *
     * @return Photo
     */
    public function setW($w)
    {
        $this->w = $w;

        return $this;
    }

    /**
     * Get w
     *
     * @return string
     */
    public function getW()
    {
        return $this->w;
    }

    /**
     * Set h
     *
     * @param integer $h
     *
     * @return Photo
     */
    public function setH($h)
    {
        $this->h = $h;

        return $this;
    }

    /**
     * Get h
     *
     * @return int
     */
    public function getH()
    {
        return $this->h;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return Photo
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set instId
     *
     * @param integer $instId
     *
     * @return Photo
     */
    public function setInstId($instId)
    {
        $this->instId = $instId;

        return $this;
    }

    /**
     * Get instId
     *
     * @return int
     */
    public function getInstId()
    {
        return $this->instId;
    }

    /**
     * Set usrId
     *
     * @param integer $usrId
     *
     * @return Photo
     */
    public function setUsrId($usrId)
    {
        $this->usrId = $usrId;

        return $this;
    }

    /**
     * Get usrId
     *
     * @return int
     */
    public function getUsrId()
    {
        return $this->usrId;
    }

    /**
     * Set sqr
     *
     * @param string $sqr
     *
     * @return Photo
     */
    public function setSqr($sqr)
    {
        $this->sqr = $sqr;

        return $this;
    }

    /**
     * Get sqr
     *
     * @return string
     */
    public function getSqr()
    {
        return $this->sqr;
    }
    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Photo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
