<?php

namespace Valantir\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Abstract class with properties and methods
 *
 * @author Kamil
 */
abstract class User {
    
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
    
    public function __construct() {
        parent::__construct();
        $this->forums = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }
    
    /**
     * Forums created by user
     * 
     * @return ArrayCollection
     */
    public function getForums() {
        return $this->forums;
    }

    /**
     * Topics created by user
     * 
     * @return ArrayCollection
     */
    public function getTopics() {
        return $this->topics;
    }

    /**
     * Posts created by user
     * 
     * @return ArrayCollection
     */
    public function getPosts() {
        return $this->posts;
    }

    /**
     * Set collection of forums
     * 
     * @param ArrayCollection $forums
     */
    public function setForums(ArrayCollection $forums) {
        $this->forums = $forums;
    }

    /**
     * Set collection of topics
     * 
     * @param ArrayCollection $forums
     */
    public function setTopics(ArrayCollection $topics) {
        $this->topics = $topics;
    }

    /**
     * Set collection of posts
     * 
     * @param ArrayCollection $forums
     */
    public function setPosts(ArrayCollection $posts) {
        $this->posts = $posts;
    }
    
    /**
     * Adds forum to collection of forums
     * 
     * @param Forum $forum
     */
    public function addForum(Forum $forum) {
        $forum->setAuthor($this);
        $this->forums->add($forum);
    }
    
    /**
     * Removes forum from collection of forums
     * 
     * @param Forum $forum
     */
    public function removeForum(Forum $forum) {
        $this->forums->removeElement($forum);
    }
    
    /**
     * Adds topic to collection of topics
     * 
     * @param Topic $topic
     */
    public function addTopic(Topic $topic) {
        $topic->setAuthor($this);
        $this->topics->add($topic);
    }
    
    /**
     * Removes topic from collection of topics
     * 
     * @param Topic $topic
     */
    public function removeTopic(Topic $topic) {
        $this->topics->removeElement($topic);
    }

    /**
     * Adds post to collection of posts
     * 
     * @param Post $post
     */
    public function addPost(Post $post) {
        $post->setAuthor($this);
        $this->posts->add($post);
    }
    
    /**
     * Removes post from collection of posts
     * 
     * @param Post $post
     */
    public function removePost(Post $post) {
        $this->posts->removeElement($post);
    }

}
