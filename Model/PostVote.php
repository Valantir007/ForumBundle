<?php

namespace Valantir\ForumBundle\Model;

use \DateTime;

/**
 * Model of PostVote
 *
 * @author Kamil
 */
class PostVote
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var boolean
     */
    protected $kind;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param User $user
     * 
     * @return PostVote
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param Post $post
     * 
     * @param PostVote
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param boolean $kind
     * 
     * @return PostVote
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * @param DateTime $createdAt
     * 
     * @return PostVote
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}