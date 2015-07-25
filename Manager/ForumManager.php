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
    public function findForums($parentSlug = null) {
        
        $qb = $this->repository->createQueryBuilder('f');
        $qb->select('f', 't', 'p.createdAt', 'COUNT(t) as topicsCount', 'COUNT(p) as postsCount')
            ->leftJoin('f.topics', 't')
            ->leftJoin('t.posts', 'p')
            ->leftJoin('f.parent', 'fp')
            ->leftJoin('p.author', 'u');
        
        if($parentSlug) {
            $qb->where('fp.slug = :parentSlug')
                ->setParameter('parentSlug', $parentSlug);
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
