<?php

namespace AppBundle\Entity;

/**
 * Category
 */
class Category
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
     * @var int
     */
    private $parent;

    /**
     * @var string
     */
    private $ukName;

     /**
     * @var array
     */
    private $children;


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
     * @return Category
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
     * Set parent
     *
     * @param integer $parent
     *
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set ukName
     *
     * @param string $ukName
     *
     * @return Category
     */
    public function setUkName($ukName)
    {
        $this->ukName = $ukName;

        return $this;
    }

    /**
     * Get ukName
     *
     * @return string
     */
    public function getUkName()
    {
        return $this->ukName;
    }

      /**
     * Set children
     *
     * @param array $children
     *
     * @return Category
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }
}

