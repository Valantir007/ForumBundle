<?php

namespace Valantir\ForumBundle\Entity;

use Valantir\ForumBundle\Model\Forum as BasicForum;

/**
 * Entity of Forum
 *
 * @author Kamil
 */
class Forum extends BasicForum {
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
