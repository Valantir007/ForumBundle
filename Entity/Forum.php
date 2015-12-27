<?php

namespace Valantir\ForumBundle\Entity;

use Valantir\ForumBundle\Model\Forum as BasicForum;
use \DateTime;

/**
 * Entity of Forum
 *
 * @author Kamil Demurat
 */
class Forum extends BasicForum
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Forum
     */
    public function prePersist()
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    /**
     * @return Forum
     */
    public function preUpdate()
    {
        $this->updatedAt = new DateTime();

        return $this;
    }
}
