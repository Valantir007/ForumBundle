<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Valantir\ForumBundle\Manager\PostVoteManager;
use Symfony\Component\HttpFoundation\Response;
use Valantir\ForumBundle\Manager\TopicManager;
use Valantir\ForumBundle\Manager\PostManager;
use Valantir\ForumBundle\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Valantir\ForumBundle\Entity\Post;
use Valantir\ForumBundle\Entity\Topic;
use Knp\Component\Pager\Paginator;
use \Exception;

/**
 * Topic controller
 *
 * @author Kamil Demurat
 */
class TopicController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var DataCollectorTranslator
     */
    protected $translator;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * List of topics in forum by forum id
     * 
     * @param int $forumId
     * 
     * @return Response
     */
    public function indexAction($forumId)
    {
        $topicsQuery = $this->getTopicManager()->findTopicsByForum($forumId);

        $perPage = 10;
        $pagination = $this->paginator->paginate(
            $topicsQuery,
            $this->request->query->getInt('page', 1),
            $perPage,
            array('wrap-queries' => true)
        );

        $topicsIds = array();
        foreach ($pagination as $topic) {
            $topicsIds[] = $topic[0]->getId();
        }

        $readedTopics = array();
        if ($this->getUser()) {
            $readedTopics = $this->getTopicManager()->readedTopics($topicsIds, $this->getUser()->getId());
        }

        $lastPosts = $this->getPostManager()->getTopicsLastPosts($topicsIds);

        return $this->render('ValantirForumBundle:Topic:index.html.twig', array(
            'topics' => $pagination,
            'lastPosts' => $lastPosts,
            'readedTopics' => $readedTopics,
            'perPage' => $perPage
        ));
    }

    /**
     * Shows topic
     * 
     * @param string   $slug
     * @param int|null $quotationPostId
     * 
     * @return Response|RedirectResponse
     * 
     * @throws Exception
     */
    public function showTopicAction($slug, $quotationPostId = null)
    {
        $topic = $this->getTopicManager()->findOneBy(array('slug' => $slug));

        if (!$topic) {
            throw $this->createNotFoundException(sprintf('Topic with slug %s does not exists', $slug));
        }

        $editingPost = $this->request->attributes->get('editPost', null);
        if ($editingPost) {
            $this->generateBreadcrumb($editingPost, $this->translator->trans('edition.post'));
            $post = $this->getPostManager()->find($editingPost); 
        } else {
            $post = new Post();
        }

        if ($quotationPostId) {
            $quotationPost = $this->getPostManager()->find($quotationPostId);
            if ($quotationPost) {
                $this->generateBreadcrumb($quotationPost, $this->translator->trans('addition/quotation.post'));
                $post->setDescription('[quote]' . $quotationPost->getDescription() . '[/quote]');
            }
        }

        if (!isset($quotationPost) && !isset($editingPost)) {
            $this->generateBreadcrumb($topic, $this->translator->trans('addition.post'));
        }

        if ($this->isLogged()) {
            $postForm = $this->createForm('post_type', $post);
            $postForm->handleRequest($this->request);

            if (!$editingPost) { //if the post is edited, form is not checked.
                if ($postForm->isValid()) {
                    try {
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
                    if ($page > 1) {
                        $templateParameters['page'] = $page;
                    }

                    return $this->redirect($this->generateUrl('topic_show', $templateParameters));
                }
            }
        }

        $pagination = $this->paginator->paginate(
            $this->getPostManager()->findPostsByTopic($topic->getId()),
            $this->request->get('page', 1),
            10,
            array('wrap-queries' => true)
        );

        $postsIds = array_map(function($post){
            return $post->getId();
        },
        $pagination->getItems());

        return $this->render('ValantirForumBundle:Topic:show.html.twig', array(
            'posts' => $pagination,
            'postForm' => ($this->isLogged()) ? $postForm->createView() : false,
            'topic' => $topic,
            'page' => $this->request->get('page', 1),
            'editingPost' => $editingPost,
            'scroll' => ($editingPost || $quotationPostId),
            'votes' => $this->getPostVoteManager()->getVotesOfPosts($postsIds)
        ));
    }

    /**
     * Deletes topic by slug
     * 
     * @param string $slug
     * 
     * @return RedirectResponse
     * 
     * @throws Exception
     */
    public function deleteTopicAction($slug)
    {
        $topic = $this->getTopicManager()->findOneBy(array('slug' => $slug));

        if (!$topic) {
            throw $this->createNotFoundException(sprintf('Topic with slug %s does not exists', $slug));
        }

        if (!$this->isLoggedAsAdmin()) {
            $this->denyAccessUnlessGranted('ROLE_FORUM_ADMIN', null, 'Unable to access this page!');
        }

        $parentSlug = $topic->getForum()->getSlug();

        try {
            $this->getTopicManager()->remove($topic);
            $this->addFlash(
                'success',
                $this->translator->trans('topic.has.been.removed')
            );
        } catch (Exception $ex) {
            $this->addFlash(
                'danger',
                $this->translator->trans('topic.has.not.been.removed')
            );
        }

        return $this->redirect($this->generateUrl('forum_index', array(
            'slug' => $parentSlug
        )));
    }

    /**
     * Checks user is logged in
     * 
     * @return boolean
     */
    protected function isLogged()
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_USER');
    }

    /**
     * Checks user is logged as admin of forum
     * 
     * @return boolean
     */
    protected function isLoggedAsAdmin()
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_FORUM_ADMIN');
    }

    /**
     * Sets topic as readed by User
     * 
     * @param Topic $topic
     */
    protected function setAsReaded(Topic $topic)
    {
        $this->getUser()->addReadedTopic($topic);
        $this->getUserManager()->update($this->getUser());
    }

    /**
     * GenerateBreadcrumb
     * 
     * @param Forum|Topic|Post $object
     * @param string|null      $lastText
     */
    protected function generateBreadcrumb($object, $lastText = null)
    {
        $this->get('breadcrumb_service')->generateBreadcrumb($object, $lastText);
    }

    /**
     * Returns topic manager
     * 
     * @return TopicManager
     */
    private function getTopicManager()
    {
        return $this->get('manager.valantir.topic');
    }

    /**
     * Returns post manager
     * 
     * @return PostManager
     */
    private function getPostManager()
    {
        return $this->get('manager.valantir.post');
    }

    /**
     * Returns post vote manager
     * 
     * @return PostVoteManager
     */
    private function getPostVoteManager()
    {
        return $this->get('manager.valantir.post.vote');
    }

    /**
     * Returns user manager
     * 
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->get('manager.valantir.user');
    }
}
