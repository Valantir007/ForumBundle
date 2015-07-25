<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Valantir\ForumBundle\Entity\Forum;
use Valantir\ForumBundle\Form\ForumType;

/**
 * Forum controller.
 *
 */
class ForumController extends Controller
{

    /**
     * List of forums
     */
    public function indexAction()
    {
        $forums = $this->getForumManager()->findAll();
        
        return $this->render('ValantirForumBundle:Forum:index.html.twig', array(
            'forums' => $forums
        ));
    }

    /**
     * Return forum manager
     * 
     * @return Valantir\ForumBundle\Manager\ForumManager
     */
    private function getForumManager() {
        return $this->get('manager.valantir.forum');
    }
}
