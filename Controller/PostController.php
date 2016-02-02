<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Valantir\ForumBundle\Manager\PostVoteManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Valantir\ForumBundle\Manager\PostManager;
use Valantir\ForumBundle\Entity\PostVote;
use Valantir\ForumBundle\Entity\Post;
use Knp\Component\Pager\Paginator;
use \Exception;

/**
 * Post controller
 *
 * @author Kamil Demurat
 */
class PostController extends Controller
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
     * Action to edit post by id
     * 
     * @param int $id
     * @param int|null $page
     * 
     * @return Response
     * 
     * @throws NotFoundHttpException
     */
    public function editAction($id, $page = null)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_FORUM_ADMIN')) {
            throw $this->createAccessDeniedException('Unable to access this page!');
        }

        $post = $this->getPostManager()->find($id);
        if(!$post) {
            throw $this->createNotFoundException(sprintf('Post with id %s does not exists', $id));
        }

        $this->get('breadcrumb_service')->generateBreadcrumb($post, $this->translator->trans('edition.post'));

        $postForm = $this->createForm('post_type', $post);
        $postForm->handleRequest($this->request);

        if ($postForm->isValid()) {
            try {
                $this->getPostManager()->update($post);
                $this->addFlash(
                    'success',
                    $this->translator->trans('post.has.been.edited')
                );
            } catch (Exception $ex) {
                $this->addFlash(
                    'danger',
                    $this->translator->trans('post.has.not.been.edited')
                );
            }
        }

        $slug = $post->getTopic()->getSlug();

        //forward to another controller, because there is logic of update and display template
        return $this->forward('ValantirForumBundle:Topic:showTopic', array('slug' => $slug, 'editPost' => $id, 'page' => $page));
    }

    /**
     * Soft removal post
     * 
     * @param int $id
     * 
     * @return RedirectResponse
     * 
     * @throws AccessDeniedException
     */
    public function removePostAction($id)
    {
        $post = $this->getPostManager()->find($id);

        if (!$post) {
            throw $this->createNotFoundException(sprintf('Post with id %s does not exists', $id));
        }

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_FORUM_ADMIN')) {
            throw $this->createAccessDeniedException('Unable to access this action!');
        }

        $topicSlug = $post->getTopic()->getSlug();

        try {
            $this->getPostManager()->remove($post);
            $this->addFlash(
                'success',
                $this->translator->trans('post.has.been.removed')
            );
        } catch (Exception $ex) {
            $this->addFlash(
                'danger',
                $this->translator->trans('post.has.not.been.removed')
            );
        }

        return $this->redirect($this->generateUrl('topic_show', array('slug' => $topicSlug)));
    }

    /**
     * Vote down
     * 
     * @param integer $id
     * 
     * @return JsonResponse|RedirectResponse
     * 
     * @throws Exception
     */
    public function voteDownAction($id)
    {
        try {
            if (!$this->isLogged()) {
                throw $this->createAccessDeniedException('Unable to access this page!');
            }

            $post = $this->getPostManager()->find($id);

            if (!$post) {
                throw $this->createNotFoundException(sprintf('Post with id %s does not exists', $id));
            }

            $ownerResult = $this->checkOwner($post);
            if ($ownerResult) {
                return $ownerResult;
            }

            $postVote = $this->getPostVoteManager()->findVoteForPost($post->getId(), $this->getUser()->getId());
            if (!$postVote) {
                $postVote = new PostVote();
                $postVote->setPost($post);
            }

            $postVote->setKind(0);
            $this->getPostVoteManager()->update($postVote);

            $result = array(
                'result' => true,
                'message' => $this->translator->trans('thank.you.for.your.vote')
            );
        } catch(Exception $ex) {
            throw $ex;
            $result = array(
                'result' => false,
                'message' => $this->translator->trans('an.error.occurred.please.try.again.later')
            );
        }

        if($this->request->isXmlHttpRequest()) {
            return new JsonResponse($result);
        }

        $this->addFlash(
            ($result['result']) ? 'success' : 'danger',
            $result['message']
        );

        return $this->redirect($this->generateUrl('topic_show', array('slug' => $post->getTopic()->getSlug())));
    }

    /**
     * Vote up
     * 
     * @param integer $id
     * 
     * @return JsonResponse|RedirectResponse
     * 
     * @throws Exception
     */
    public function voteUpAction($id) {
        try {
            if (!$this->isLogged()) {
                throw $this->createAccessDeniedException('Unable to access this page!');
            }

            $post = $this->getPostManager()->find($id);

            if (!$post) {
                throw $this->createNotFoundException(sprintf('Post with id %s does not exists', $id));
            }

            $ownerResult = $this->checkOwner($post);
            if ($ownerResult) {
                return $ownerResult;
            }

            $postVote = $this->getPostVoteManager()->findVoteForPost($post->getId(), $this->getUser()->getId());
            if (!$postVote) {
                $postVote = new PostVote();
                $postVote->setPost($post);
            }

            $postVote->setKind(1);
            $this->getPostVoteManager()->update($postVote);

            $result = array(
                'result' => true,
                'message' => $this->translator->trans('thank.you.for.your.vote')
            );
        } catch(Exception $ex) {
            throw $ex;
            $result = array(
                'result' => false,
                'message' => $this->translator->trans('an.error.occurred.please.try.again.later')
            );
        }

        if($this->request->isXmlHttpRequest()) {
            return new JsonResponse($result);
        }

        $this->addFlash(
            ($result['result']) ? 'success' : 'danger',
            $result['message']
        );
        
        return $this->redirect($this->generateUrl('topic_show', array('slug' => $post->getTopic()->getSlug())));
    }

    /**
     * Checks logged user is owner of voted post
     * 
     * @param Post $post
     * 
     * @return JsonResponse|RedirectResponse|null
     */
    private function checkOwner(Post $post) {
        if ($this->getUser()) {
            if ($post->getAuthor()->getId() == $this->getUser()->getId()) {
                $message = $this->translator->trans('you.can.not.vote.for.your.posts');
                $this->addFlash(
                    'danger',
                    $message
                );

                if($this->request->isXmlHttpRequest()) {
                    return new JsonResponse(array('result' => false, 'message' => $message));
                }

                return $this->redirect($this->generateUrl('topic_show', array('slug' => $post->getTopic()->getSlug())));
            }
        }

        return null;
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
     * Checks user is logged in
     * 
     * @return boolean
     */
    protected function isLogged()
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_USER');
    }
}
