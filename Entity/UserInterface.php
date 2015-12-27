<?php

namespace Valantir\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface for User class
 *
 * @author Kamil Demurat
 */
interface UserInterface
{
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
     * Returns avatar path
     * 
     * @return string
     */
    public function getAvatar();

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

    /**
     * Gets collection of readed topics
     * 
     * @return ArrayCollection
     */
    public function getReadedTopics();

    /**
     * Sets collection of readed topics
     * 
     * @param ArrayCollection $readedTopics
     */
    public function setReadedTopics($readedTopics);

    /**
     * Adds topic to collection of readed topics
     * 
     * @param Topic $readedTopic
     */
    public function addReadedTopic(Topic $readedTopic);

    /**
     * Removes topic from collection of readed topics
     * 
     * @param Topic $readedTopic
     */
    public function removeReadedTopic(Topic $readedTopic);
}
