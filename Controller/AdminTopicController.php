<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Exception;

/**
 * Admin Forum controller.
 *
 */
class AdminTopicController extends Controller
{

    protected $request;
    
    protected $translator;
    
    protected $paginator;
    
    public function indexAction($forumId) {
        $topicsQuery = $this->getTopicManager()->findTopicsByForum($forumId);
        
        $pagination = $this->paginator->paginate(
            $topicsQuery,
            $this->request->query->getInt('page', 1),
            10
        );
        
        $topicsIds = array();
        foreach($pagination as $topic) {
            $topicsIds[] = $topic[0]->getId();
        }
        
        $lastPosts = $this->getPostManager()->getTopicsLastPosts($topicsIds);

        return $this->render('ValantirForumBundle:Topic/Admin:index.html.twig', array(
            'topics' => $pagination,
            'lastPosts' => $lastPosts
        ));
    }
    
    /**
     * Returns topic manager
     * 
     * @return Valantir\ForumBundle\Manager\TopicManager
     */
    private function getTopicManager() {
        return $this->get('manager.valantir.topic');
    }
    
    /**
     * Returns post manager
     * 
     * @return Valantir\ForumBundle\Manager\PostManager
     */
    private function getPostManager() {
        return $this->get('manager.valantir.post');
    }
}
