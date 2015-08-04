<?php

namespace Valantir\ForumBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * User manager
 *
 * @author Kamil
 */
class UserManager extends BasicManager {
    
    public function __construct(EntityManager $em, $class, $container) {
        $forumConfig = $container->getParameter('valantir_forum');
        parent::__construct($em, $forumConfig['user_class']);
    }
}
