<?php

namespace Valantir\ForumBundle\Doctrine\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Mapping listener class
 *
 * @author Kamil
 */
class MappingListener {
    
    /**
     * Container
     * 
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * Default user class
     * 
     * @var string
     */
    protected $defaultUserClass = 'Valantir\ForumBundle\Entity\User';
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    /**
     * Method to change User class for entities with user association
     * 
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {
        
        $classMetadata = $eventArgs->getClassMetadata();
        $assocName = $classMetadata->getAssociationNames();
        if(!$assocName) {
            return;
        }
        
        foreach($assocName as $name) {
            if($this->getTargetClass($classMetadata, $name) == $this->defaultUserClass) {
                $classMetadata->associationMappings[$name]['targetEntity'] = $this->getUserClass();
            }
        }
    }
    
    /**
     * Get user class from forum config 
     * 
     * @return string
     */
    public function getUserClass() {
        $forumConfig = $this->container->getParameter('valantir_forum');
        return $forumConfig['user_class'];
    }
    
    /**
     * Return Target Class for association
     * 
     * @param string $fieldName
     * @param type $classMetadata
     * @return string
     */
    public function getTargetClass(ClassMetadata $classMetadata, $fieldName) {
        if(isset($classMetadata->associationMappings[$fieldName])) {
            return $classMetadata->associationMappings[$fieldName]['targetEntity'];
        }
        
        return '';
    }
}
