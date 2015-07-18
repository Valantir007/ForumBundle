<?php

namespace Valantir\ForumBundle\Entity;

use Valantir\ForumBundle\Model\Post as BasicPost;

/**
 * Entity of Post
 *
 * @author Kamil
 */
class Post extends BasicPost {
    public function __toString() {
        return $this->name;
    }
    
    public function prePersist() {
        $this->createdAt = new \DateTime();
    }
    
    public function preUpdate() {
        $this->updatedAt = new \DateTime();
    }
}
