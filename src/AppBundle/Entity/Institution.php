<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\WorkingHours;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Institution
 */
class Institution
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $photoId;

    /**
     * @var string
     */
    private $address;

    /**
     * @var int
     */
    private $isActivated;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lng;

    /**
     * @var integer
     */
    private $owner;

    /**
     * @var integer
     */
    private $categoryId;

    /**
     * @ORM\OneToMany(targetEntity="WorkingHours", mappedBy="institution")
     */
    private $workingTime;

    /**
     * @ORM\OneToMany(targetEntity="PhoneNumber", mappedBy="institution")
     */
    private $phoneNumbers;

    /**
     * @var integer
     */
    private $recruitFrom;

    /**
     * @var integer
     */
    private $recruitTo;

    public function __construct()
    {
        $this->workingTime = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Institution
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Institution
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
     * Set photoId
     *
     * @param integer $photoId
     *
     * @return Institution
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;

        return $this;
    }

    /**
     * Get photoId
     *
     * @return int
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Institution
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set isActivated
     *
     * @param integer $isActivated
     *
     * @return Institution
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * Get isActivated
     *
     * @return int
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Institution
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }


    /**
     * Set categoryId
     *
     * @param integer $categoryId
     *
     * @return Institution
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }


    /**
     * Set owner
     *
     * @param integer $owner
     *
     * @return Institution
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return integer
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * @var float
     */


    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Institution
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }
    /**
     * @var string
     */
    private $oneToMany;


    /**
     * Set oneToMany
     *
     * @param string $oneToMany
     *
     * @return Institution
     */
    public function setOneToMany($oneToMany)
    {
        $this->oneToMany = $oneToMany;

        return $this;
    }

    /**
     * Get oneToMany
     *
     * @return string
     */
    public function getOneToMany()
    {
        return $this->oneToMany;
    }

    /**
     * Add workingTime
     *
     * @param \AppBundle\Entity\WorkingHours $workingTime
     *
     * @return Institution
     */
    public function addWorkingTime(\AppBundle\Entity\WorkingHours $workingTime)
    {
        $this->workingTime[] = $workingTime;

        return $this;
    }

    /**
     * Remove workingTime
     *
     * @param \AppBundle\Entity\WorkingHours $workingTime
     */
    public function removeWorkingTime(\AppBundle\Entity\WorkingHours $workingTime)
    {
        $this->workingTime->removeElement($workingTime);
    }

    /**
     * Get workingTime
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkingTime()
    {
        return $this->workingTime;
    }

    /**
     * Set recruitFrom
     *
     * @param integer $recruitFrom
     *
     * @return Institution
     */
    public function setRecruitFrom($recruitFrom)
    {
        $this->recruitFrom = $recruitFrom;

        return $this;
    }

    /**
     * Get recruitFrom
     *
     * @return integer
     */
    public function getRecruitFrom()
    {
        return $this->recruitFrom;
    }

    /**
     * Set recruitTo
     *
     * @param integer $recruitTo
     *
     * @return Institution
     */
    public function setRecruitTo($recruitTo)
    {
        $this->recruitTo = $recruitTo;

        return $this;
    }

    /**
     * Get recruitTo
     *
     * @return integer
     */
    public function getRecruitTo()
    {
        return $this->recruitTo;
    }

    /**
     * Add phoneNumber
     *
     * @param \AppBundle\Entity\PhoneNumber $phoneNumber
     *
     * @return Institution
     */
    public function addPhoneNumber(\AppBundle\Entity\PhoneNumber $phoneNumber)
    {
        $this->phoneNumbers[] = $phoneNumber;

        return $this;
    }

    /**
     * Remove phoneNumber
     *
     * @param \AppBundle\Entity\PhoneNumber $phoneNumber
     */
    public function removePhoneNumber(\AppBundle\Entity\PhoneNumber $phoneNumber)
    {
        $this->phoneNumbers->removeElement($phoneNumber);
    }

    /**
     * Get phoneNumbers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }
    /**
     * @var string
     */
    private $imgUrl;


    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     *
     * @return Institution
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
     * @var integer
     */
    private $locationId;


    /**
     * Set locationId
     *
     * @param integer $locationId
     *
     * @return Institution
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * Get locationId
     *
     * @return integer
     */
    public function getLocationId()
    {
        return $this->locationId;
    }
}
