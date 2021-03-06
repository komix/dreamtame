<?php

namespace AppBundle\Entity;

/**
 * Video
 */
class Video
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $imgUrl;

    /**
     * @var string
     */
    private $ytbUrl;

    /**
     * @var string
     */
    private $ytbId;

    /**
     * @var string
     */
    private $instance;

    /**
     * @var int
     */
    private $instanceId;


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
     * Set imgUrl
     *
     * @param string $imgUrl
     *
     * @return Video
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set ytbUrl
     *
     * @param string $ytbUrl
     *
     * @return Video
     */
    public function setYtbUrl($ytbUrl)
    {
        $this->ytbUrl = $ytbUrl;

        return $this;
    }

    /**
     * Get ytbUrl
     *
     * @return string
     */
    public function getYtbUrl()
    {
        return $this->ytbUrl;
    }

    /**
     * Set ytbId
     *
     * @param string $ytbId
     *
     * @return Video
     */
    public function setYtbId($ytbId)
    {
        $this->ytbId = $ytbId;

        return $this;
    }

    /**
     * Get ytbId
     *
     * @return string
     */
    public function getYtbId()
    {
        return $this->ytbId;
    }

    /**
     * Set instance
     *
     * @param string $instance
     *
     * @return Video
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return string
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set instanceId
     *
     * @param integer $instanceId
     *
     * @return Video
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;

        return $this;
    }

    /**
     * Get instanceId
     *
     * @return int
     */
    public function getInstanceId()
    {
        return $this->instanceId;
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
     * @return Video
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
