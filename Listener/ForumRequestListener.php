<?php

namespace Valantir\ForumBundle\Listener;

use Valantir\ForumBundle\Manager\UserManager;
use Valantir\ForumBundle\Manager\TopicManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Listener to set readed topic by user
 *
 * @author Kamil Demurat
 */
class ForumRequestListener
{
    /**
     * @var TopicManager 
     */
    protected $topicManager;

    /**
     * @var UserManager 
     */
    protected $userManager;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var array
     */
    protected $readedTopicRoutes = array(
        'topic_show' => 'slug',
        'topic_show_quotation' => 'slug',
        'post_edit' => 'id'
    );

    /**
     * @param TopicManager    $topicManager
     * @param UserManager     $userManager
     * @param SecurityContext $securityContext
     */
    public function __construct(TopicManager $topicManager, UserManager $userManager, SecurityContext $securityContext)
    {
        $this->topicManager = $topicManager;
        $this->userManager = $userManager;
        $this->securityContext = $securityContext;
    }

    /**
     * @param GetResponseEvent $event
     * 
     * @return null
     */
    public function onRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $token = $this->securityContext->getToken();

        $request = $event->getRequest();
        if (isset($this->readedTopicRoutes[$request->get('_route')]) && $token && $user = $token->getUser()) {
            $parameterName = $this->readedTopicRoutes[$request->get('_route')];

            $topic = $this->getTopic($request->get('_route'), $parameterName, $request->get($parameterName));
            if ($this->securityContext->isGranted('ROLE_USER')) {
                if ($topic && $user) {
                    $user->addReadedTopic($topic);
                    $this->userManager->update($user);
                }
            }
        }
    }

    /**
     * Returns current topic
     * 
     * @param string $route
     * @param string $parameterName
     * @param int    $value
     * 
     * @return Topic|null
     */
    protected function getTopic($route, $parameterName, $value) {
        if ($route === 'post_edit') {
            return $this->getTopicByPost($value);
        }

        return $this->getTopicBy($parameterName, $value);
    }
    
    /**
     * Gets Topic by $paramName and $value
     * 
     * @param string $paramName
     * @param int    $value
     * 
     * @return Topic|null
     */
    protected function getTopicBy($paramName, $value)
    {
        return $this->topicManager->findOneBy(array($paramName => $value));
    }
    
    /**
     * Returns Topic by post id
     * 
     * @param int $value
     * 
     * @return Topic
     */
    protected function getTopicByPost($value)
    {
        return $this->topicManager->findOneByPost($value);
    }
}