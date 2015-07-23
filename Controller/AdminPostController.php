<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Valantir\ForumBundle\Entity\Post;
use \Exception;

/**
 * Admin Forum controller.
 *
 */
class AdminPostController extends Controller
{

    protected $request;
    
    protected $translator;
    
    protected $paginator;
    
    public function indexAction($topicId) {
        
        $topic = $this->getTopicManager()->find($topicId);
        
        if(!$topic) {
            throw $this->createNotFoundException(sprintf('Topic with id %s does not exists', $topicId));
        }
        
        $postsQuery = $this->getPostManager()->findPostsByTopic($topicId);
        
        $post = new Post();
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
            
            $templateParameters = array('topicId' => $topicId);
            //we check page and if page > 1 then add page parameter to route
            $page = ceil($this->getPostManager()->countPostsInTopic($topicId)/10);
            if($page > 1) {
                $templateParameters['page'] = $page;
            }
            
            return $this->redirect($this->generateUrl('admin_post_index', $templateParameters));
        }
        
        $pagination = $this->paginator->paginate(
            $postsQuery,
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render('ValantirForumBundle:Post/Admin:index.html.twig', array(
            'posts' => $pagination,
            'postForm' => $postForm->createView()
        ));
    }
    
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
