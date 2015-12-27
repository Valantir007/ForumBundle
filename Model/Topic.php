<?php

namespace Valantir\ForumBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

/**
 * Model of Topic
 *
 * @author Kamil
 */
class Topic
{
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
     * @var Forum
     */
    protected $forum;

    /**
     *
     * @var ArrayCollection
     */
    protected $posts;

    /**
     * @var Object - user class
     */
    protected $author;

    /**
     *
     * @var ArrayCollection
     */
    protected $readers;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->readers = new ArrayCollection();
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
    public function getSlug()
    {
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
     * @param string $name
     * 
     * @return Topic
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $slug
     * 
     * @return Topic
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param DateTime $createdAt
     * 
     * @return Topic
     */
    public function setCreatedAt(DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param DateTime $updatedAt
     * 
     * @return Topic
     */
    public function setUpdatedAt(DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Forum
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * @param Forum $forum
     * 
     * @return Topic
     */
    public function setForum(Forum $forum = null)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     * 
     * @return Topic
     */
    public function setPosts(ArrayCollection $posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @param Post $post
     * 
     * @return Topic
     */
    public function addPost(Post $post) {
        $post->setTopic($this);
        $this->posts->add($post);

        return $this;
    }

    /**
     * @param Post $post
     * 
     * @return Topic
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     * @return Object - user object
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Object $author - user object
     * 
     * @return Topic
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
     * @return Topic
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * @return Topic
     */
    public function setDeletedAt(DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @param ArrayCollection $readers
     * 
     * @return Topic
     */
    public function setReaders(ArrayCollection $readers)
    {
        $this->readers = $readers;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getReaders()
    {
        return $this->readers;
    }

    /**
     * @param Object $reader - user object
     */
    public function addReader($reader)
    {
        $this->readers->add($reader);
    }

    /**
     * @param Object $reader - user object
     * 
     * @return Topic
     */
    public function removeReader($reader)
    {
        $this->readers->removeElement($reader);

        return $this;
    }
}
