<?php

namespace Valantir\ForumBundle\Entity;

use Valantir\ForumBundle\Model\Topic as BasicTopic;

/**
 * Entity of Topic
 *
 * @author Kamil
 */
class Topic extends BasicTopic {
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
