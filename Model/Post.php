<?php

namespace Valantir\ForumBundle\Model;

/**
 * Model of Post
 *
 * @author Kamil
 */
class Post {
    protected $id;
    
    protected $description;
    
    protected $createdAt;
    
    protected $updatedAt;
    
    protected $deletedAt;
    
    protected $topic;
    
    protected $author;
    
    public function getId() {
        return $this->id;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
    
    public function getTopic() {
        return $this->topic;
    }

    public function setTopic(Topic $topic) {
        $this->topic = $topic;
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
