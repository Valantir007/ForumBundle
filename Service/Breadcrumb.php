<?php

namespace Valantir\ForumBundle\Service;

use Valantir\ForumBundle\Entity\Post;
use Valantir\ForumBundle\Entity\Forum;
use Valantir\ForumBundle\Entity\Topic;
use Valantir\ForumBundle\Manager\PostManager;
use Valantir\ForumBundle\Manager\ForumManager;
use Valantir\ForumBundle\Manager\TopicManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class to generate breadcrumb in forum
 *
 * @author Kamil Demurat
 */
class Breadcrumb
{
    /**
     * Items in breadcrumb
     * 
     * @var array
     */
    protected $items = array();

    /**
     * @var ContainerInterface 
     */
    protected $container;

    /**
     * @var Router
     */
    protected $router;

    /**
     * Sets container and router
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $this->container->get('router');
        $this->addFirstItem();
    }

    /**
     * Adds items to breadcrumb
     * 
     * @param Forum|Topic|Post $object
     * @param string $lastText
     * 
     * @return null - only if object is null
     */
    public function generateBreadcrumb($object, $lastText = null)
    {
        if (!$object) {
            return null;
        }

        switch (true) {
            case ($object instanceof Forum):
                $this->generateBreadcrumb($object->getParent());
                $this->addItem('forum_index', $object);
                $this->addLastItem($lastText);
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
     * @return Breadcrumb
     */
    protected function addFirstItem()
    {
        $this->items[] = array(
            'url' => $this->router->generate('forum_index'),
            'name' => $this->container->get('translator')->trans('forum.list'),
        );

        return $this;
    }

    /**
     * Adds item to breadcrumb
     * 
     * @param string $routeName
     * @param mixed  $object
     * 
     * @return Breadcrumb
     */
    public function addItem($routeName, $object)
    {
        $this->items[] = array(
            'url' => $this->router->generate($routeName, array('slug' => $object->getSlug()), UrlGeneratorInterface::ABSOLUTE_PATH),
            'name' => $object->getName(),
        );

        return $this;
    }

    /**
     * Adds last element to breadcrumb
     * 
     * @param string $lastText
     * 
     * @return Breadcrumb
     */
    protected function addLastItem($lastText = null)
    {
        if ($lastText) {
            $this->items[] = array(
                'url' => null,
                'name' => $lastText
            );
        }

        return $this;
    }

    /**
     * Returns breadcrumbs items
     * 
     * @return array
     */
    public function getBreadcrumb()
    {
        return $this->items;
    }

    /**
     * Returns manager to forum
     * 
     * @return ForumManager
     */
    protected function getForumManager()
    {
        return $this->container->get('manager.valantir.forum');
    }

    /**
     * Returns manager to topic
     * 
     * @return TopicManager
     */
    protected function getTopicManager()
    {
        return $this->container->get('manager.valantir.topic');
    }

    /**
     * Returns manager to post
     * 
     * @return PostManager
     */
    protected function getPostManager()
    {
        return $this->container->get('manager.valantir.post');
    }
}
