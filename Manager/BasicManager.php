<?php

namespace Valantir\ForumBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Description of BasicManager
 *
 * @author Kamil
 */
abstract class BasicManager{
    
    protected $em;
    protected $repository;
    protected $class;
    
    public function __construct(EntityManager $em, $class) {
        $this->em           = $em;
        $this->class        = $class;
        $this->repository   = $em->getRepository($this->class);
    }
    
    public function remove($object, $flush = true) {
        $this->em->remove($object);
        if($flush)
        {
            $this->em->flush();
        }
    }
    
    public function refresh($object) {
        $this->em->refresh($object);
    }
    
    public function update($object, $flush = true) {
        $this->em->persist($object);
        if($flush)
        {
            $this->em->flush();
        }
    }
    
    public function flush() {
        $this->em->flush();
    }
    
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }
    
    public function findAll() {
        return $this->repository->findAll();
    }
    
    public function findOneBy($values) {
        return $this->repository->findOneBy($values);
    }
    
    public function find($id) {
        return $this->repository->find($id);
    }
}