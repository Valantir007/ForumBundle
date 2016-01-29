<?php

namespace Valantir\ForumBundle\Entity;

use Valantir\ForumBundle\Model\Topic as BasicTopic;
use \DateTime;

/**
 * Entity of Topic
 *
 * @author Kamil Demurat
 */
class Topic extends BasicTopic
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->getPosts()->isEmpty()) {
            $this->addPost(new Post());
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Topic
     */
    public function prePersist()
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    /**
     * @return Topic
     */
    public function preUpdate()
    {
        $this->updatedAt = new DateTime();

        return $this;
    }
}
