<?php

namespace Valantir\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller {
    public function indexAction() {
        return $this->render('ValantirForumBundle:Forum/Admin:index.html.twig');
    }
}
