<?php

namespace Valantir\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Abstract class with properties and methods
 *
 * @author Kamil Demurat
 */
abstract class User
{
    /**
     * Forums created by user
     * 
     * @var ArrayCollection
     */
    protected $forums;

    /**
     * Topics created by user
     * 
     * @var ArrayCollection
     */
    protected $topics;

    /**
     * Posts created by user
     * 
     * @var ArrayCollection
     */
    protected $posts;

    /**
     * Topics readed by user
     * 
     * @var ArrayCollection
     */
    protected $readedTopics;

    public function __construct()
    {
        parent::__construct();
        $this->forums = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->readedTopics = new ArrayCollection();
    }

    /**
     * Forums created by user
     * 
     * @return ArrayCollection
     */
    public function getForums()
    {
        return $this->forums;
    }

    /**
     * Topics created by user
     * 
     * @return ArrayCollection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Posts created by user
     * 
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Sets collection of forums
     * 
     * @param ArrayCollection $forums
     */
    public function setForums(ArrayCollection $forums)
    {
        $this->forums = $forums;

        return $this;
    }

    /**
     * Sets collection of topics
     * 
     * @param ArrayCollection $forums
     */
    public function setTopics(ArrayCollection $topics)
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * Set collection of posts
     * 
     * @param ArrayCollection $forums
     */
    public function setPosts(ArrayCollection $posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Adds forum to collection of forums
     * 
     * @param Forum $forum
     */
    public function addForum(Forum $forum)
    {
        $forum->setAuthor($this);
        $this->forums->add($forum);

        return $this;
    }

    /**
     * Removes forum from collection of forums
     * 
     * @param Forum $forum
     */
    public function removeForum(Forum $forum)
    {
        $this->forums->removeElement($forum);

        return $this;
    }

    /**
     * Adds topic to collection of topics
     * 
     * @param Topic $topic
     */
    public function addTopic(Topic $topic)
    {
        $topic->setAuthor($this);
        $this->topics->add($topic);

        return $this;
    }
    
    /**
     * Removes topic from collection of topics
     * 
     * @param Topic $topic
     */
    public function removeTopic(Topic $topic)
    {
        $this->topics->removeElement($topic);

        return $this;
    }

    /**
     * Adds post to collection of posts
     * 
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        $post->setAuthor($this);
        $this->posts->add($post);

        return $this;
    }

    /**
     * Removes post from collection of posts
     * 
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     * Gets collection of readed topics
     * 
     * @return ArrayCollection
     */
    public function getReadedTopics()
    {
        return $this->readedTopics;
    }

    /**
     * Sets collection of readed topics
     * 
     * @param ArrayCollection $readedTopics
     */
    public function setReadedTopics($readedTopics)
    {
        $this->readedTopics = $readedTopics;

        return $this;
    }

    /**
     * Adds topic to collection of readed topics
     * 
     * @param Topic $readedTopic
     */
    public function addReadedTopic(Topic $readedTopic)
    {
        if (!$this->readedTopics->contains($readedTopic)) {
            $readedTopic->addReader($this);
            $this->readedTopics->add($readedTopic);
        }

        return $this;
    }

    /**
     * Removes topic from collection of readed topics
     * 
     * @param Topic $readedTopic
     */
    public function removeReadedTopic(Topic $readedTopic)
    {
        $this->readedTopics->removeElement($readedTopic);

        return $this;
    }

}
