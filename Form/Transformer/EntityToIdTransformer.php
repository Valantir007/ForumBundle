<?php

namespace Valantir\ForumBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class EntityToIdTransformer implements DataTransformerInterface {
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    public function __construct(ObjectManager $objectManager, $class) {
        $this->objectManager = $objectManager;
        $this->class = $class;
    }

    public function transform($entity) {
//        echo 'transform';
//        wypluj($entity);
        if (null === $entity) {
            return;
        }
        
        return $entity->getId();
    }

    public function reverseTransform($id) {
//        echo 'revert';
//        wypluj($id);
        if (!$id) {
            return null;
        }
        
        $entity = $this->objectManager
            ->getRepository($this->class)
            ->find($id);

        if (null === $entity) {
            throw new TransformationFailedException();
        }
        
        return $entity;
    }
}