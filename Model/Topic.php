<?php

namespace Valantir\ForumBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model of Topic
 *
 * @author Kamil
 */
class Topic {
    
    protected $id;
    
    protected $name;
    
    protected $description;
    
    protected $createdAt;
    
    protected $updatedAt;
    
    protected $deletedAt;
    
    protected $category;
    
    protected $posts;
    
    protected $author;
    
    public function __construct() {
        $this->posts = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
    
    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }
    
    public function getPosts() {
        return $this->posts;
    }

    public function setPosts($posts) {
        $this->posts = $posts;
    }

    public function addPost(Post $post) {
        $post->setTopic($this);
        $this->posts->add($post);
    }
    
    public function removePost(Post $post) {
        $this->posts->removeElement($post);
    }
    
    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }
    
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }
}
