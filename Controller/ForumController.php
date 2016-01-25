<?php

namespace Valantir\ForumBundle\Controller;

use \Exception;
use Symfony\Component\Form\Form;
use Knp\Component\Pager\Paginator;
use Valantir\ForumBundle\Entity\Forum;
use Valantir\ForumBundle\Entity\Topic;
use Valantir\ForumBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Valantir\ForumBundle\Manager\PostManager;
use Symfony\Component\HttpFoundation\Response;
use Valantir\ForumBundle\Manager\TopicManager;
use Valantir\ForumBundle\Manager\ForumManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\DataCollectorTranslator;

/**
 * Forum controller
 *
 * @author Kamil Demurat
 */
class ForumController extends Controller
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
     * List of forums with topic and forum forms
     * 
     * @param string $slug
     * 
     * @return Response
     */
    public function indexAction($slug = null)
    {
        $currentForum = $this->getForumManager()->findOneBy(array('slug' => $slug));
        $this->get('breadcrumb_service')->generateBreadcrumb($currentForum); //generate breadcrumb

        $forumsQuery = $this->getForumManager()->findForums(($slug) ? $slug : null); //we create query and pass to paginator

        if ($this->isLoggedAsAdmin()) {
            $forum = new Forum();
            $forumForm = $this->createForm('forum_type', $forum);
            $this->addForum($forumForm, $forum, ($currentForum) ? $currentForum->getId() : null); //call method to add forum
        }

        $topicForm = false;
        if ($this->isLogged()) {
            $topic = new Topic();
            $topic->setForum($currentForum);
            $post = new Post();
            $post->setAuthor($this->getUser());
            $topic->addPost($post);

            $topicForm = $this->createForm('topic_type', $topic);        
            $this->addTopic($topicForm, $topic, ($currentForum) ? $currentForum->getId() : null); //call method to add topic
        }

        $pagination = $this->paginator->paginate(
            $forumsQuery,
            $this->request->query->getInt('page', 1),
            10,
            array('wrap-queries' => true)
        );

        $forumsIds = array();

        foreach ($pagination->getItems() as $paginationForum) {
            $forumsIds[] = $paginationForum[0]->getId();
        }

        $lastPosts = $this->getPostManager()->getForumsLastPosts($forumsIds); //gets last post per forum

        return $this->render('ValantirForumBundle:Forum:index.html.twig', array(
            'forums' => $pagination,
            'forumForm' => ($this->get('security.authorization_checker')->isGranted('ROLE_FORUM_ADMIN')) ? $forumForm->createView() : null,
            'topicForm' => ($topicForm && $currentForum && $currentForum->getId()) ? $topicForm->createView() : null,
            'lastPosts' => $lastPosts,
            'forumId' => ($currentForum) ? $currentForum->getId() : null
        ));
    }

    /**
     * Deletes forum by slug
     * 
     * @param string $slug
     * 
     * @return RedirectResponse
     * 
     * @throws Exception
     */
    public function deleteForumAction($slug)
    {
        $forum = $this->getForumManager()->findOneBy(array('slug' => $slug));

        if (!$forum) {
            throw $this->createNotFoundException(sprintf('Forum with slug %s does not exists', $slug));
        }

        if (!$this->isLoggedAsAdmin()) {
            $this->denyAccessUnlessGranted('ROLE_FORUM_ADMIN', null, 'Unable to access this page!');
        }

        $parentSlug = ($forum->getParent()) ? $forum->getParent()->getSlug() : null;

        try {
            $this->getForumManager()->remove($forum);
            $this->addFlash(
                'success',
                $this->translator->trans('forum.has.been.removed')
            );
        } catch (Exception $ex) {
            $this->addFlash(
                'danger',
                $this->translator->trans('forum.has.not.been.removed')
            );
        }

        return $this->redirect($this->generateUrl('forum_index', array(
            'slug' => $parentSlug
        )));
    }

    /**
     * Adds Forum to database if form is valid
     * 
     * @param Form  $forumForm
     * @param Forum $forum
     * @param int   $parent
     * 
     * @return RedirectResponse|null
     */
    protected function addForum(Form $forumForm, Forum $forum, $parent)
    {
        $forumForm->handleRequest($this->request);
        if ($forumForm->isValid()) {
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

        return;
    }

    /**
     * Adds Topic to database if form is valid
     * 
     * @param Form  $topicForm
     * @param Topic $topic
     * @param int   $parent
     * 
     * @return RedirectResponse|null
     */
    protected function addTopic(Form $topicForm, Topic $topic, $parent)
    {
        $topicForm->handleRequest($this->request);
        if ($topicForm->isValid()) {
            try {
                $topic->setAuthor($this->getUser());
                $this->getTopicManager()->update($topic);
                $this->addFlash(
                    'success',
                    $this->translator->trans('topic.has.been.created')
                );
            } catch (Exception $ex) {
                throw $ex;
                $this->addFlash(
                    'danger',
                    $this->translator->trans('topic.has.not.been.created')
                );
            }

            return $this->redirect($this->generateUrl('forum_index', array(
                'parent' => $parent
            )));
        }

        return;
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
     * Returns forum manager
     * 
     * @return ForumManager
     */
    private function getForumManager()
    {
        return $this->get('manager.valantir.forum');
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
}
