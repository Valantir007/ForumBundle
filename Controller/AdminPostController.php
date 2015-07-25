<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Admin Forum controller.
 *
 */
class AdminPostController extends Controller
{

    protected $request;
    
    protected $translator;
    
    protected $paginator;
    
    /**
     * Returns post manager
     * 
     * @return Valantir\ForumBundle\Manager\PostManager
     */
    private function getPostManager() {
        return $this->get('manager.valantir.post');
    }
    
    /**
     * Returns topic manager
     * 
     * @return Valantir\ForumBundle\Manager\TopicManager
     */
    private function getTopicManager() {
        return $this->get('manager.valantir.topic');
    }
}
