<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TopicController extends Controller {
    public function indexAction() {
        return $this->render('ValantirForumBundle:Topic:index.html.twig');
    }
}
