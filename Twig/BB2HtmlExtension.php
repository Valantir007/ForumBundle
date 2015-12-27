<?php

namespace Valantir\ForumBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class to parse bbcode to html
 *
 * @author Kamil Demurat
 */
class BB2HtmlExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

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
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('bb2html', array($this, 'bb2html')),
        );
    }

    /**
     * @param string $text
     * 
     * @return string
     */
    public function bb2html($text)
    {
        return $this->container->get('bb_code_parser')->bb2html($text);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bb2html';
    }
}