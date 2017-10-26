<?php
// src/AppBundle/Entity/User.php
 
namespace AppBundle\Entity;
 
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var integer
     */
    private $photoId;

    /**
     * @var string
     */
    private $smallPhotoUrl;

    public function setEmail($email){
        parent::setEmail($email);
        parent::setUsername($email);
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
     * Set photoId
     *
     * @param integer $photoId
     *
     * @return User
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;

        return $this;
    }

    /**
     * Get photoId
     *
     * @return integer
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set smallPhotoUrl
     *
     * @param string $smallPhotoUrl
     *
     * @return User
     */
    public function setSmallPhotoUrl($smallPhotoUrl)
    {
        $this->smallPhotoUrl = $smallPhotoUrl;

        return $this;
    }

    /**
     * Get smallPhotoUrl
     *
     * @return string
     */
    public function getSmallPhotoUrl()
    {
        return $this->smallPhotoUrl;
    }
}
