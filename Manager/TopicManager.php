<?php

namespace Valantir\ForumBundle\Manager;

/**
 * Topics manager
 *
 * @author Kamil
 */
class TopicManager extends BasicManager {
    /**
     * Returns topics by forum id
     * 
     * @param int $forumId
     * @return Doctrine\ORM\Query
     */
    public function findTopicsByForum($forumId) {
        $qb = $this->repository->createQueryBuilder('t');
        $qb->select('t', 'p.createdAt', 'COUNT(p) as postsCount')
            ->leftJoin('t.forum', 'f')
            ->leftJoin('t.posts', 'p')
            ->leftJoin('p.author', 'u')
            ->where('f.id = :forumId')
            ->setParameter('forumId', $forumId);
        
        $qb->groupBy('t.id')
            ->orderBy('t.id')
            ->addOrderBy('p.createdAt', 'DESC');
        
        $query = $qb->getQuery();
        return $query;
    }
}
