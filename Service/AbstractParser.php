<?php

namespace Valantir\ForumBundle\Service;

/**
 * Abstract class for parser
 *
 * @author Kamil
 */
abstract class AbstractParser implements BBCodeParserInterface
{
    /**
     * @var array
     */
    protected $availableTags = array();

    /**
     * @return array
     */
    public function getAvailableTags()
    {
        return $this->availableTags;
    }

    /**
     * @param array $availableTags
     */
    public function setAvailableTags(array $availableTags = array())
    {
        $this->availableTags = $availableTags;
    }
}
