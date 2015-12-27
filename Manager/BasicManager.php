<?php

namespace Valantir\ForumBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Basic class to set managers for each forum entity
 *
 * @author Kamil Demurat
 */
abstract class BasicManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param EntityManager $em
     * @param string        $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em           = $em;
        $this->class        = $class;
        $this->repository   = $em->getRepository($this->class);
    }

    /**
     * @param Object $object
     * @param boolean $flush
     */
    public function remove($object, $flush = true)
    {
        $this->em->remove($object);
        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * @param Object $object
     */
    public function refresh($object)
    {
        $this->em->refresh($object);
    }

    /**
     * @param Object $object
     * @param boolean $flush
     */
    public function update($object, $flush = true)
    {
        $this->em->persist($object);
        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Flushes all changes to objects
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * @param array        $criteria
     * @param string|null  $orderBy
     * @param integer|null $limit
     * @param integer|null $offset
     * 
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $values
     * 
     * @return Object|null
     */
    public function findOneBy($values)
    {
        return $this->repository->findOneBy($values);
    }

    /**
     * @param integer $id
     * 
     * @return Object
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }
}