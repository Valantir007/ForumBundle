<?php

namespace Valantir\ForumBundle\Controller;

use \Exception;
use Symfony\Component\Form\Form;
use Knp\Component\Pager\Paginator;
use Valantir\ForumBundle\Entity\Forum;
use Valantir\ForumBundle\Entity\Topic;
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
     * List of forums with topics and forum forms
     * 
     * @param string $slug
     * 
     * @return Response
     */
    public function indexAction($slug = null)
    {
        $currentForum = $this->getForumManager()->findOneBy(array('slug' => $slug));
        
        $this->get('breadcrumb_service')->generateBreadcrumb($currentForum); //generate breadcrumb

        if ($this->isLoggedAsAdmin()) {
            $forum = new Forum();
            $forumForm = $this->createForm('forum_type', $forum);
            $addResult = $this->addForum($forumForm, $forum, ($currentForum) ? $currentForum->getSlug() : null); //call method to add forum

            if ($addResult instanceof RedirectResponse) { //if added correctly or feilure, redirect
                return $addResult;
            }
        }

        $topicForm = false;
        if ($this->isLogged() && $currentForum && $currentForum->getParent()) {
            $topic = new Topic();
            $topic->setForum($currentForum);
            $topicForm = $this->createForm('topic_type', $topic);
            $addResult = $this->addTopic($topicForm, $topic, ($currentForum) ? $currentForum->getSlug() : null); //call method to add topic

            if ($addResult instanceof RedirectResponse) { //if added correctly or feilure, redirect
                return $addResult;
            }
        }

        $perPage = 10;
        $pagination = $this->paginator->paginate(
            $this->getForumManager()->findForums(($slug) ? $slug : null), //we create query and pass to paginator
            $this->request->query->getInt('page', 1),
            $perPage,
            array('wrap-queries' => true)
        );

        $forumsIds = array();

        foreach ($pagination->getItems() as $paginationForum) {
            $forum = $paginationForum[0];
            $forumsIds[] = $forum->getId();
            foreach ($forum->getChildren() as $child) {
                $forumsIds[] = $child->getId();
            }
        }

        return $this->render('ValantirForumBundle:Forum:index.html.twig', array(
            'forums' => $pagination,
            'forumForm' => ($this->get('security.authorization_checker')->isGranted('ROLE_FORUM_ADMIN')) ? $forumForm->createView() : null,
            'topicForm' => ($topicForm && $currentForum && $currentForum->getId()) ? $topicForm->createView() : null,
            'lastPosts' => $this->getPostManager()->getForumsLastPosts($forumsIds), //gets last post per forum
            'forumId' => ($currentForum) ? $currentForum->getId() : null,
            'counts' => $this->getForumManager()->countTopicsAndPosts($slug), //counts of topics and posts
            'perPage' => $perPage,
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
     * @param string   $parentSlug
     * 
     * @return RedirectResponse|null
     */
    protected function addForum(Form $forumForm, Forum $forum, $parentSlug)
    {
        $forumForm->handleRequest($this->request);
        if ($forumForm->isValid()) {
            try {
                $this->getDoctrine()->getManager()->getFilters()->disable('softdeleteable');
                $this->getForumManager()->update($forum);
                $this->getDoctrine()->getManager()->getFilters()->enable('softdeleteable');

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
                'slug' => $parentSlug
            )));
        }

        return;
    }

    /**
     * Adds Topic to database if form is valid
     * 
     * @param Form  $topicForm
     * @param Topic $topic
     * @param string   $parentSlug
     * 
     * @return RedirectResponse|null
     */
    protected function addTopic(Form $topicForm, Topic $topic, $parentSlug)
    {
        $topicForm->handleRequest($this->request);
        if ($topicForm->isValid()) {
            try {
                $this->getDoctrine()->getManager()->getFilters()->disable('softdeleteable');
                $this->getTopicManager()->update($topic);
                $this->getDoctrine()->getManager()->getFilters()->enable('softdeleteable');

                $this->addFlash(
                    'success',
                    $this->translator->trans('topic.has.been.created')
                );

                return $this->redirect($this->generateUrl('topic_show', array(
                    'slug' => $topic->getSlug()
                )));
            } catch (Exception $ex) {
                throw $ex;
                $this->addFlash(
                    'danger',
                    $this->translator->trans('topic.has.not.been.created')
                );

                return $this->redirect($this->generateUrl('forum_index', array(
                    'slug' => $parentSlug
                )));
            }
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
