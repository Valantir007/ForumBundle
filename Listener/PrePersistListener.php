<?php

namespace Valantir\ForumBundle\Listener;

use Valantir\ForumBundle\Entity\Post;
use Valantir\ForumBundle\Entity\PostVote;
use Valantir\ForumBundle\Entity\Forum;
use Valantir\ForumBundle\Entity\Topic;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Listener to set author for forum component
 *
 * @author Kamil Demurat
 */
class PrePersistListener
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param LifecycleEventArgs $event
     * 
     * @return null
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $entity = $args->getEntity();
            $user = $token->getUser();
            if ($entity instanceof Forum || $entity instanceof Topic || $entity instanceof Post) {
                $entity->setAuthor($user);
            }

            if ($entity instanceof PostVote) {
                $entity->setUser($user);
            }
        }
    }
}