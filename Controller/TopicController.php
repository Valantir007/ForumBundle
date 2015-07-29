<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Valantir\ForumBundle\Entity\Post;
use \Exception;

/**
 * Topic controller.
 */
class TopicController extends Controller
{

    protected $request;
    
    protected $translator;
    
    protected $paginator;
    
    /**
     * List of topics in forum by forum id
     * 
     * @param int $forumId
     * @return Symfony\Component\HttpFoundation\Response
     */
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
        return $this->render('ValantirForumBundle:Topic:index.html.twig', array(
            'topics' => $pagination,
            'lastPosts' => $lastPosts
        ));
    }
    
    /**
     * Show topic
     * 
     * @param int $topicId
     * @param int $quotationPostId
     * @return Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function showTopicAction($slug, $quotationPostId = null) {
        $topic = $this->getTopicManager()->findOneBy(array('slug' => $slug));
        
        if(!$topic) {
            throw $this->createNotFoundException(sprintf('Topic with slug %s does not exists', $slug));
        }
        
        $editingPost = $this->request->attributes->get('editPost', null);
        if($editingPost) {
            $post = $this->getPostManager()->find($editingPost); 
        } else {
            $post = new Post();
        }
        
        if($quotationPostId) {
            $quotationPost = $this->getPostManager()->find($quotationPostId);
            if($quotationPost) {
                $post->setDescription('[quote]' . $quotationPost->getDescription() . '[/quote]');
            }
        }
        
        $postForm = $this->createForm('post_type', $post);
        $postForm->handleRequest($this->request);
        
        if($postForm->isValid()) {
            try {
                $post->setAuthor($this->getUser());
                $post->setTopic($topic);
                $this->getPostManager()->update($post);
                $this->addFlash(
                    'success',
                    $this->translator->trans('post.has.been.created')
                );
            } catch (Exception $ex) {
                $this->addFlash(
                    'danger',
                    $this->translator->trans('post.has.not.been.created')
                );
            }
            
            $templateParameters = array('slug' => $topic->getSlug());
            //we check page and if page > 1 then add page parameter to route
            $page = ceil($this->getPostManager()->countPostsInTopic($topic->getId())/10);
            if($page > 1) {
                $templateParameters['page'] = $page;
            }
            
            return $this->redirect($this->generateUrl('topic_show', $templateParameters));
        }
        
        $postsQuery = $this->getPostManager()->findPostsByTopic($topic->getId());

        $pagination = $this->paginator->paginate(
            $postsQuery,
            $this->request->get('page', 1),
            10
        );

        return $this->render('ValantirForumBundle:Topic:show.html.twig', array(
            'posts' => $pagination,
            'postForm' => $postForm->createView(),
            'topic' => $topic,
            'page' => $this->request->get('page', 1),
            'editingPost' => $editingPost
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
