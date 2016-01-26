<?php

namespace Valantir\ForumBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Valantir\ForumBundle\Service\BBCodeParserInterface;

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
     * @var BBCodeParserInterface
     */
    protected $parser;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $bundleConfiguration = $this->container->getParameter('valantir_forum');
        $this->parser = $this->container->get($bundleConfiguration['parser']);
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
        return $this->parser->bb2html($text);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bb2html';
    }
}