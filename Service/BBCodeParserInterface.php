<?php

namespace Valantir\ForumBundle\Service;

/**
 * Interface for bbcode parsers
 *
 * @author Kamil
 */
interface BBCodeParserInterface
{
    /**
     * Method to convert bbcode to html
     * 
     * @param string $text
     * 
     * @return string
     */
    public function bb2html($text);
}
