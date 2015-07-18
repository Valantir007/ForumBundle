<?php

namespace Valantir\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @author Kamil
 */
interface UserInterface {
    
    /**
     * Returns user email
     * 
     * @return string
     */
    public function getEmail();
    
    /**
     * Returns username
     * 
     * @return string
     */
    public function getUsername();
    
    /**
     * Forums created by user
     * 
     * @return ArrayCollection
     */
    public function getForums();
    
    /**
     * Topics created by user
     * 
     * @return ArrayCollection
     */
    public function getTopics();
    
    /**
     * Posts created by user
     * 
     * @return ArrayCollection
     */
    public function getPosts();
    
    /**
     * Set collection of forums
     * 
     * @param ArrayCollection $forums
     */
    public function setForums(ArrayCollection $forums);
    
    /**
     * Set collection of topics
     * 
     * @param ArrayCollection $forums
     */
    public function setTopics(ArrayCollection $topics);
    
    /**
     * Set collection of posts
     * 
     * @param ArrayCollection $forums
     */
    public function setPosts(ArrayCollection $posts);
    
    /**
     * Adds forum to collection of forums
     * 
     * @param Forum $forum
     */
    public function addForum(Forum $forum);
    
    /**
     * Removes forum from collection of forums
     * 
     * @param Forum $forum
     */
    public function removeForum(Forum $forum);
    
    /**
     * Adds topic to collection of topics
     * 
     * @param Topic $topic
     */
    public function addTopic(Topic $topic);
    
    /**
     * Removes topic from collection of topics
     * 
     * @param Topic $topic
     */
    public function removeTopic(Topic $topic);
    
    /**
     * Adds post to collection of posts
     * 
     * @param Post $post
     */
    public function addPost(Post $post);
    
    /**
     * Removes post from collection of posts
     * 
     * @param Post $post
     */
    public function removePost(Post $post);
}
