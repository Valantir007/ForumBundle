<?php

namespace Valantir\ForumBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class co parse bbcode to html
 *
 * @author Kamil
 */
class BreadcrumbExtension extends \Twig_Extension
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('breadcrumb', array($this, 'breadcrumb'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @return string
     */
    public function breadcrumb()
    {
        $breadcrumb = $this->container->get('breadcrumb_service')->getBreadcrumb();

        return $this->container->get('templating')->render('ValantirForumBundle::breadcrumb.html.twig', array(
            'breadcrumb' => $breadcrumb
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'breadcrumb';
    }
}