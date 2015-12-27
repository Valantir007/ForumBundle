<?php

namespace Valantir\ForumBundle\Model;

use \DateTime;

/**
 * Model of Post
 *
 * @author Kamil
 */
class Post
{
    /**
     * @var integer
     */
    protected $id;

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
     * @var Topic
     */
    protected $topic;

    /**
     * @var Object - user object
     */
    protected $author;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @param DateTime $createdAt
     * 
     * @return Post
     */
    public function setCreatedAt(DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param DateTime $updatedAt
     * 
     * @return Post
     */
    public function setUpdatedAt(DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     * 
     * @return Post
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;

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
     * @param Object $author - user object
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = strip_tags($description);

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
     * @return Post
     */
    public function setDeletedAt(DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}