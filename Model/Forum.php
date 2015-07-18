<?php

namespace Valantir\ForumBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model of Forum
 *
 * @author Kamil
 */
class Forum {
    protected $id;
    
    protected $name;
    
    protected $left;
    
    protected $level;
    
    protected $right;
    
    protected $root;
    
    protected $description;
    
    protected $createdAt;
    
    protected $updatedAt;
    
    protected $deletedAt;
    
    protected $topics;
    
    protected $parent;
    
    protected $children;
    
    protected $author;
    
    public function __construct() {
        $this->topics = new ArrayCollection();
        $this->children = new ArrayCollection();
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
    
    public function getTopics() {
        return $this->topics;
    }

    public function setTopics($topics) {
        $this->topics = $topics;
    }

    public function addTopic(Topic $topic) {
        $topic->setCategory($this);
        $this->topics->add($topic);
    }
    
    public function removeTopic(Topic $topic) {
        $this->topics->removeElement($topic);
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

    public function getParent() {
        return $this->parent;
    }

    public function getChildren() {
        return $this->children;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function setChildren($children) {
        $this->children = $children;
    }
    
    public function getLeft() {
        return $this->left;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getRight() {
        return $this->right;
    }

    public function getRoot() {
        return $this->root;
    }

    public function setLeft($left) {
        $this->left = $left;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setRight($right) {
        $this->right = $right;
    }

    public function setRoot($root) {
        $this->root = $root;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }
}
