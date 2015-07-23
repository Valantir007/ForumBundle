<?php

namespace Valantir\ForumBundle\Manager;

/**
 * Forums manager
 *
 * @author Kamil
 */
class ForumManager extends BasicManager {
    
    /**
     * Gets all forums by parent
     * 
     * @param int|null $parent
     * @return Doctrine\ORM\Query
     */
    public function findForums($parent = null) {
        
        $qb = $this->repository->createQueryBuilder('f');
        $qb->select('f', 't', 'p.createdAt', 'COUNT(t) as topicsCount', 'COUNT(p) as postsCount')
            ->leftJoin('f.topics', 't')
            ->leftJoin('t.posts', 'p')
            ->leftJoin('p.author', 'u');
        
        if($parent) {
            $qb->where('f.parent = :parent')
                ->setParameter('parent', $parent);
        } else {
            $qb->where('f.parent IS NULL');
        }
        
        $qb->groupBy('f.id')
            ->orderBy('f.id')
            ->addOrderBy('p.createdAt', 'DESC');
        
        $query = $qb->getQuery();
        return $query;
    }
}
