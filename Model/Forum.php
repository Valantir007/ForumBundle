<?php

namespace Valantir\ForumBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

/**
 * Model of Forum
 *
 * @author Kamil Demurat
 */
class Forum {
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var integer
     */
    protected $left;

    /**
     * @var integer
     */
    protected $level;

    /**
     * @var integer
     */
    protected $right;

    /**
     * @var integer
     */
    protected $root;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @var DateTime
     */
    protected $deletedAt;

    /**
     * @var ArrayCollection
     */
    protected $topics;

    /**
     * @var Forum
     */
    protected $parent;

    /**
     * @var ArrayCollection
     */
    protected $children;

    /**
     * @var Object - user entity class
     */
    protected $author;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string
     * 
     * @return Forum
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string
     * 
     * @return Forum
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param DateTime
     * 
     * @return Forum
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param DateTime
     * 
     * @return Forum
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * @param ArrayCollection $topics
     * 
     * @return Forum
     */
    public function setTopics(ArrayCollection $topics)
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * @param Topic $topic
     * 
     * @return Forum
     */
    public function addTopic(Topic $topic)
    {
        $topic->setCategory($this);
        $this->topics->add($topic);

        return $this;
    }

    /**
     * @param Topic $topic
     * 
     * @return Forum
     */
    public function removeTopic(Topic $topic)
    {
        $this->topics->removeElement($topic);

        return $this;
    }

    /**
     * @return Object - user class
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Object $author - user class
     * 
     * @return Forum
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * 
     * @return Forum
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Forum
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Forum $parent
     * 
     * @return Forum
     */
    public function setParent(Forum $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param ArrayCollection $children
     * 
     * @return Forum
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return integer
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param integer $left
     * 
     * @return Forum
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * @param integer $level
     * 
     * @return Forum
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @param integer $right
     * 
     * @return Forum
     */
    public function setRight($right)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * @param integer $root
     * 
     * @return Forum
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTime $deletedAt
     * 
     * @return Forum
     */
    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
