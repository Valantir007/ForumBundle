<?php

namespace Valantir\ForumBundle\Entity;

use Valantir\ForumBundle\Model\PostVote as BasicPostVote;
use \DateTime;

/**
 * Entity of PostVote
 *
 * @author Kamil
 */
class PostVote extends BasicPostVote
{
    /**
     * @return Post
     */
    public function prePersist()
    {
        $this->createdAt = new DateTime();

        return $this;
    }
}