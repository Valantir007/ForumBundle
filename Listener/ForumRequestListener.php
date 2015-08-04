<?php

namespace Valantir\ForumBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ForumRequestListener {
    
    protected $topicManager;
    
    protected $userManager;
    
    protected $securityContext;
        
    protected $readedTopicRoutes = array(
        'topic_show' => 'slug',
        'topic_show_quotation' => 'slug',
        'post_edit' => 'id'
    );
    
    public function __construct($topicManager, $userManager, $securityContext) {
        $this->topicManager = $topicManager;
        $this->userManager = $userManager;
        $this->securityContext = $securityContext;
    }
    
    public function onRequest(GetResponseEvent $event) {
        
        if ($event->isMasterRequest()) {
            $token = $this->securityContext->getToken();
            
            $request = $event->getRequest();
            if(isset($this->readedTopicRoutes[$request->get('_route')]) && $token && $user = $token->getUser()) {
                $parameterName = $this->readedTopicRoutes[$request->get('_route')];
                
                $topic = $this->getTopic($request->get('_route'), $parameterName, $request->get($parameterName));
                
                if($topic && $user) {
                    $user->addReadedTopic($topic);
                    $this->userManager->update($user);
                }
            }
            
            return;
        }
    }
    
    /**
     * Returns current topic
     * 
     * @param string $route
     * @param string $parameterName - field
     * @param int $value
     * @return Topic|null
     */
    protected function getTopic($route, $parameterName, $value) {
        if($route == 'post_edit') {
            return $this->getTopicByPost($value);
        }
        return $this->getTopicBy($parameterName, $value);
    }
    
    /**
     * Gets Topic by $paramName and $value
     * 
     * @param string $paramName
     * @param int $value
     * @return Topic|null
     */
    protected function getTopicBy($paramName, $value) {
        return $this->topicManager->findOneBy(array($paramName => $value));
    }
    
    /**
     * Returns Topic by post id
     * 
     * @param int $value
     * @return Topic
     */
    protected function getTopicByPost($value) {
        return $this->topicManager->findOneByPost($value);
    }
}