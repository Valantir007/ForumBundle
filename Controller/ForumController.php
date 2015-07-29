<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Valantir\ForumBundle\Entity\Forum;
use Valantir\ForumBundle\Entity\Topic;
use Symfony\Component\Form\Form;
use \Exception;

/**
 * Forum controller.
 */
class ForumController extends Controller
{

    protected $request;
    
    protected $translator;
    
    protected $paginator;
    
    /**
     * List of forums with topic and forum forms
     * 
     * @param string $slug
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($slug = null)
    {
        $isAdmin = false;
        if($this->get('security.context')->isGranted('ROLE_FORUM_ADMIN')) {
            $isAdmin = true;
        }
        
        $forumsQuery = $this->getForumManager()->findForums(($slug) ? $slug : null); //we create query and pass to paginator
        $currentForum = $this->getForumManager()->findOneBy(array('slug' => $slug));
        
        if($isAdmin) {
            $forum = new Forum();
            $forumForm = $this->createForm('forum_type', $forum);
            $this->addForum($forumForm, $forum, ($currentForum) ? $currentForum->getId() : null); //call method to add forum
        }
        
        $topic = new Topic();
        $topic->setForum($currentForum);
        $topicForm = $this->createForm('topic_type', $topic);        
        $this->addTopic($topicForm, $topic, ($currentForum) ? $currentForum->getId() : null); //call method to add topic
        
        $pagination = $this->paginator->paginate(
            $forumsQuery,
            $this->request->query->getInt('page', 1),
            10
        );
        
        $forumsIds = array();
        foreach($pagination as $forum) {
            $forumsIds[] = $forum[0]->getId();
        }
        
        $lastPosts = $this->getPostManager()->getForumsLastPosts($forumsIds); //gets last post per forum
        
        return $this->render('ValantirForumBundle:Forum:index.html.twig', array(
            'forums' => $pagination,
            'forumForm' => ($isAdmin) ? $forumForm->createView() : null,
            'topicForm' => ($currentForum && $currentForum->getId()) ? $topicForm->createView() : null,
            'lastPosts' => $lastPosts,
            'forumId' => ($currentForum) ? $currentForum->getId() : null
        ));
    }

    /**
     * Add Forum to database if form is valid
     * 
     * @param \Symfony\Component\Form\Form $forumForm
     * @param \Valantir\ForumBundle\Entity\Forum $forum
     * @param int $parent
     * @return Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function addForum(Form $forumForm, Forum $forum, $parent) {
        $forumForm->handleRequest($this->request);
        if($forumForm->isValid()) {
            try {
                $forum->setAuthor($this->getUser());
                $this->getForumManager()->update($forum);
                $this->addFlash(
                    'success',
                    $this->translator->trans('forum.has.been.created')
                );
            } catch (Exception $ex) {
                $this->addFlash(
                    'danger',
                    $this->translator->trans('forum.has.not.been.created')
                );
            }

            return $this->redirect($this->generateUrl('forum_index', array(
                'parent' => $parent
            )));
        }
    }
    
    /**
     * Add Topic to database if form is valid
     * 
     * @param \Symfony\Component\Form\Form $topicForm
     * @param \Valantir\ForumBundle\Entity\Topic $topic
     * @param int $parent
     * @return Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function addTopic(Form $topicForm, Topic $topic, $parent) {
        $topicForm->handleRequest($this->request);
        if($topicForm->isValid()) {
            try {
                $topic->setAuthor($this->getUser());
                $this->getTopicManager()->update($topic);
                $this->addFlash(
                    'success',
                    $this->translator->trans('topic.has.been.created')
                );
            } catch (Exception $ex) {
                $this->addFlash(
                    'danger',
                    $this->translator->trans('topic.has.not.been.created')
                );
            }
            
            return $this->redirect($this->generateUrl('forum_index', array(
                'parent' => $parent
            )));
        }
    }
    
    /**
     * Returns forum manager
     * 
     * @return Valantir\ForumBundle\Manager\ForumManager
     */
    private function getForumManager() {
        return $this->get('manager.valantir.forum');
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
