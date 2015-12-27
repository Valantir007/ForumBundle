<?php

namespace Valantir\ForumBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Transforms entity to id and id to entity
 *
 * @author Kamil Demurat
 */
class EntityToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param ObjectManager $objectManager
     * @param string        $class
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        $this->objectManager = $objectManager;
        $this->class = $class;
    }

    /**
     * @param Object $entity
     * 
     * @return int|null
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return;
        }

        return $entity->getId();
    }

    /**
     * @param integer $id
     * 
     * @return Object
     * 
     * @throws TransformationFailedException
     */
    public function reverseTransform($id) {
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