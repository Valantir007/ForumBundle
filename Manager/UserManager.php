<?php

namespace Valantir\ForumBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * User manager
 *
 * @author Kamil Demurat
 */
class UserManager extends BasicManager
{
    /**
     * @param EntityManager      $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $forumConfig = $container->getParameter('valantir_forum');
        parent::__construct($em, $forumConfig['user_class']);
    }
}
