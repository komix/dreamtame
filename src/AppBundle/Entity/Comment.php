<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Institution;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comment
 */
class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
    * @ORM\ManyToOne(targetEntity="Institution", inversedBy="comments")
    * @ORM\JoinColumn(name="institution_id", referencedColumnName="id")
    */
    private $institution;

    /**
    * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
    * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
    */
    private $article;

    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $author;

    /**
     * @var integer
     */
    private $institutionId;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="original")
     */
    private $comments;

    public function __construct()
     {
       $this->answers = new ArrayCollection();
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
     * Set text
     *
     * @param string $text
     *
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set institution
     *
     * @param \AppBundle\Entity\Institution $institution
     *
     * @return Comment
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
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Comment
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set institutionId
     *
     * @param integer $institutionId
     *
     * @return Comment
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
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $answers;

    /**
     * @var \AppBundle\Entity\Comment
     */
    private $original;


    /**
     * Add answer
     *
     * @param \AppBundle\Entity\Comment $answer
     *
     * @return Comment
     */
    public function addAnswer(\AppBundle\Entity\Comment $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \AppBundle\Entity\Comment $answer
     */
    public function removeAnswer(\AppBundle\Entity\Comment $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set original
     *
     * @param \AppBundle\Entity\Comment $original
     *
     * @return Comment
     */
    public function setOriginal(\AppBundle\Entity\Comment $original = null)
    {
        $this->original = $original;

        return $this;
    }

    /**
     * Get original
     *
     * @return \AppBundle\Entity\Comment
     */
    public function getOriginal()
    {
        return $this->original;
    }
    /**
     * @var integer
     */
    private $commentId;


    /**
     * Set commentId
     *
     * @param integer $commentId
     *
     * @return Comment
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;

        return $this;
    }

    /**
     * Get commentId
     *
     * @return integer
     */
    public function getCommentId()
    {
        return $this->commentId;
    }
    /**
     * @var integer
     */
    private $articleId;


    /**
     * Set articleId
     *
     * @param integer $articleId
     *
     * @return Comment
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Comment
     */
    public function setArticle(\AppBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}
