<?php

namespace Valantir\ForumBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Valantir\ForumBundle\Entity\Forum;
use Valantir\ForumBundle\Entity\Topic;
use Valantir\ForumBundle\Entity\Post;

/**
 * Class to generate breadcrumb in forum
 *
 * @author Kamil
 */
class Breadcrumb {
    /**
     * Items in breadcrumb
     * @var array
     */
    protected $items = array();
    
    /**
     * Container
     * 
     * @var ContainerInterface 
     */
    protected $container;
    
    /**
     * Router
     * 
     * @var Symfony\Bundle\FrameworkBundle\Routing\Router 
     */
    protected $router;
    
    /**
     * Set container and router
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->router = $this->container->get('router');
    }
    
    /**
     * Adds items to breadcrumb
     * 
     * @param Forum|Topic|Post $object
     * @param string $lastText
     * @return null - only if object is null
     */
    public function generateBreadcrumb($object, $lastText = null) {
        if(!$object) {
            return null;
        }
        
        switch (true) {
            case ($object instanceof Forum):
                $forums = $this->getForumManager()->findAncestors($object->getRoot(), $object->getRight());
                if($forums) {
                    foreach($forums as $forum) {
                        $this->addItem('forum_index', $forum);
                    }
                }
                break;
            case ($object instanceof Topic):
                $this->generateBreadcrumb($object->getForum());
                $this->addItem('topic_show', $object);
                $this->addLastItem($lastText);
                break;
            case ($object instanceof Post):
                $this->generateBreadcrumb($object->getTopic());
                $this->addLastItem($lastText);
                break;
            default:
                break;
        }
    }
    
    /**
     * Adds item to breadcrumb
     * 
     * @param type $routeName
     * @param type $object
     */
    public function addItem($routeName, $object) {
        $this->items[] = array(
            'url' => $this->router->generate($routeName, array('slug' => $object->getSlug()), UrlGeneratorInterface::ABSOLUTE_PATH),
            'name' => $object->getName()
        );
    }
    
    /**
     * Adds last element to breadcrumb
     * 
     * @param string $lastText
     */
    protected function addLastItem($lastText = null) {
        if($lastText) {
            $this->items[] = array(
                'url' => null,
                'name' => $lastText
            );
        }
    }
    
    /**
     * Returns breadcrumbs items
     * 
     * @return array
     */
    public function getBreadcrumb() {
        return $this->items;
    }
    
    /**
     * Returns manager to forum
     * 
     * @return Valantir\ForumBundle\Manager\ForumManager
     */
    protected function getForumManager() {
        return $this->container->get('manager.valantir.forum');
    }
    
    /**
     * Returns manager to topic
     * 
     * @return Valantir\ForumBundle\Manager\TopicManager
     */
    protected function getTopicManager() {
        return $this->container->get('manager.valantir.topic');
    }
    
    /**
     * Returns manager to post
     * 
     * @return Valantir\ForumBundle\Manager\PostManager
     */
    protected function getPostManager() {
        return $this->container->get('manager.valantir.post');
    }
}
