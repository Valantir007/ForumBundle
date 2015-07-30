<?php

namespace Valantir\ForumBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class to parse bbcode to html
 *
 * @author Kamil
 */
class BB2HtmlExtension extends \Twig_Extension
{
    protected $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('bb2html', array($this, 'bb2html')),
        );
    }

    public function bb2html($text) {
        return $this->container->get('bb_code_parser')->bb2html($text);
    }

    public function getName() {
        return 'bb2html';
    }
}