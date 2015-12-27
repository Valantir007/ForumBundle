<?php

namespace Valantir\ForumBundle\Doctrine\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class to dynamic mapping forum entities with user class
 *
 * @author Kamil Demurat
 */
class MappingListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $defaultUserClass = 'Valantir\ForumBundle\Entity\User';

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Method to change User class for entities with user association
     * 
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $assocName = $classMetadata->getAssociationNames();

        if ($classMetadata->getName() === $this->getUserClass()) {
            $this->setReadedTopicsAssociation($classMetadata, $eventArgs);
            $this->setForumAssociation($classMetadata);
            $this->setTopicAssociation($classMetadata);
            $this->setPostAssociation($classMetadata);
        }

        if (!$assocName) {
            return;
        }

        foreach ($assocName as $name) {
            if ($this->getTargetClass($classMetadata, $name) === $this->defaultUserClass) {
                $classMetadata->associationMappings[$name]['targetEntity'] = $this->getUserClass();
            }
        }
    }

    /**
     * Get user class from forum config 
     * 
     * @return string
     */
    protected function getUserClass()
    {
        $forumConfig = $this->container->getParameter('valantir_forum');
        return $forumConfig['user_class'];
    }

    /**
     * Return Target Class for association
     * 
     * @param string $fieldName
     * @param type   $classMetadata
     * 
     * @return string
     */
    protected function getTargetClass(ClassMetadata $classMetadata, $fieldName) {
        if (isset($classMetadata->associationMappings[$fieldName])) {
            return $classMetadata->associationMappings[$fieldName]['targetEntity'];
        }

        return '';
    }

    /**
     * Adds readed topics association mapping to User class
     * 
     * @param ClassMetadata              $classMetadata
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    protected function setReadedTopicsAssociation(ClassMetadata $classMetadata, LoadClassMetadataEventArgs $eventArgs) {
        $classMetadata->mapManyToMany(array(
            'fieldName' => 'readedTopics',
            'mappedBy' => 'readers',
            'targetEntity' => 'Valantir\ForumBundle\Entity\Topic',
        ));
    }

    /**
     * Adds forum association mapping to User class
     * 
     * @param ClassMetadata $classMetadata
     */
    protected function setForumAssociation(ClassMetadata $classMetadata)
    {
        $classMetadata->mapOneToMany(array(
            'targetEntity' => 'Valantir\ForumBundle\Entity\Forum',
            'fieldName' => 'forums',
            'mappedBy' => 'author',
        ));
    }

    /**
     * Adds topic association mapping to User class
     * 
     * @param ClassMetadata $classMetadata
     */
    protected function setTopicAssociation(ClassMetadata $classMetadata)
    {
        $classMetadata->mapOneToMany(array(
            'targetEntity' => 'Valantir\ForumBundle\Entity\Topic',
            'fieldName' => 'topics',
            'mappedBy' => 'author',
        ));
    }

    /**
     * Adds post association mapping to User class
     * 
     * @param ClassMetadata $classMetadata
     */
    protected function setPostAssociation(ClassMetadata $classMetadata)
    {
        $classMetadata->mapOneToMany(array(
            'targetEntity' => 'Valantir\ForumBundle\Entity\Post',
            'fieldName' => 'posts',
            'mappedBy' => 'author',
        ));
    }
}
