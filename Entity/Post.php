<?php

namespace Valantir\ForumBundle\Entity;

use Valantir\ForumBundle\Model\Post as BasicPost;
use \DateTime;

/**
 * Entity of Post
 *
 * @author Kamil Demurat
 */
class Post extends BasicPost
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->description;
    }

    /**
     * @return Post
     */
    public function prePersist()
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    /**
     * @return Post
     */
    public function preUpdate()
    {
        $this->updatedAt = new DateTime();

        return $this;
    }
}
