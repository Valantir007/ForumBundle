<?php

namespace Valantir\ForumBundle\Manager;

use Valantir\ForumBundle\Entity\Forum;

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
        $qb->select('f', 't', 'p.createdAt', 'COUNT(DISTINCT t) as topicsCount', 'COUNT(DISTINCT p) as postsCount')
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
    
    /**
     * Find Ancestors by root and right
     * 
     * @param int $root
     * @param int $right
     * @return array
     */
    public function findAncestors($root, $right) {
        $qb = $this->repository->createQueryBuilder('f');
        $qb->select('f')
            ->where('f.root = :root')
            ->andWhere('f.right >= :right')
            ->setParameters(array(
                'root' => $root,
                'right' => $right
            ))
            ->orderBy('f.left');
        $query = $qb->getQuery();
        return $query->getResult();
    }
    
    /**
     * 
     * @param Forum|null $forum
     * @param int $userId
     */
    public function readedForums(Forum $forum = null, $userId) {
        
        $qb = $this->repository->createQueryBuilder('f');
        $level = ($forum) ? $forum->getLevel() : 0;
        $qb->select('f.id', 'COUNT(t.id) AS quantity')
            ->leftJoin('f.topics', 't')
            ->where('f.level >= :level')
            ->groupBy('f.id')
            ->setParameters(array(
                'level' => $level
            ));
        $query = $qb->getQuery();
        debug($query->getResult());
//        $qb->select('f.id AS forumId, u.id AS userId, COUNT(t.id) AS quantity, COUNT(u.id) AS user')
//            ->leftJoin('f.topics', 't')
//            ->leftJoin('t.readers', 'u')
//            ->where('u.id = :user')
//            ->andWhere($qb->expr()->in('f.id', ':forums'))
//            ->setParameters(array(
//                'forums' => $forumsIds,
//                'user' => $userId
//            ));
//        $query = $qb->getQuery();
////        echo($query->getSql());
//        wypluj($query->getResult());
//        return $query->getResult();
    }
}
