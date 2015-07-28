<?php

namespace Valantir\ForumBundle\Service;

/**
 * Description of Breadcrumb
 *
 * @author Kamil
 */
class Breadcrumb {
    
    protected $items = array();
    
    public function addItem($name, $label, $url) {
        $this->items[$name] = array(
            'label' => $label,
            'url' => $url
        );
    }
    
    public function removeItem($name) {
        if(isset($this->items[$name])) {
            unset($this->items[$name]);
        }
    }
}
