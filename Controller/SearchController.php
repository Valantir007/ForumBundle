<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\Paginator;

/**
 * Controller for search
 *
 * @author Kamil
 */
class SearchController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * Displays search form
     * 
     * @return Response
     */
    public function searchFormAction()
    {
        $searchForm = $this->createForm('search_type');

        return $this->render('ValantirForumBundle:Search:search-form.html.twig', array(
            'form' => $searchForm->createView()
        ));
    }

    public function searchAction()
    {
        $phrase = $this->request->get('search_type[phrase]', false, true);

        if (empty($phrase)) {
            return $this->render('ValantirForumBundle:Search:search-result.html.twig', array(
                'phrase' => $phrase,
            ));
        }

        $searchQuery = $this->getTopicManager()->findWith($phrase);
        
        $pagination = $this->paginator->paginate(
            $searchQuery,
            $this->request->get('page', 1),
            10,
            array('wrap-queries' => true)
        );
        
        return $this->render('ValantirForumBundle:Search:search-result.html.twig', array(
            'topics' => $pagination,
            'phrase' => $phrase,
        ));
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
}
