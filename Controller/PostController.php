<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Valantir\ForumBundle\Manager\PostManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\DataCollectorTranslator;
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
        if(!$this->get('security.context')->isGranted('ROLE_FORUM_ADMIN')) {
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

        //forward to another controller, becouse there is logic of update and display template
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

        if (!$this->get('security.context')->isGranted('ROLE_FORUM_ADMIN')) {
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
     * Returns post manager
     * 
     * @return PostManager
     */
    private function getPostManager()
    {
        return $this->get('manager.valantir.post');
    }
}
