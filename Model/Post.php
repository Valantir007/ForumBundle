<?php

namespace Valantir\ForumBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection
     */
    protected $votes;

    public function __construct() {
        $this->votes = new ArrayCollection();
    }
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

    /**
     * Gets collection of votes
     * 
     * @return ArrayCollection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Sets collection of votes
     * 
     * @param ArrayCollection $votes
     * 
     * @return Post
     */
    public function setVotes(ArrayCollection $votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Adds post vote to collection
     * 
     * @param PostVote $vote
     * 
     * @return Post
     */
    public function addVote(PostVote $vote)
    {
        if (!$this->postsVotes->contains($vote)) {
            $vote->addPost($this);
            $this->postsVotes->add($vote);
        }

        return $this;
    }

    /**
     * Removes post vote from collection
     * 
     * @param PostVote $vote
     * 
     * @return User
     */
    public function removeVote(PostVote $vote)
    {
        $this->votes->removeElement($vote);

        return $this;
    }
}