<?php

namespace Valantir\ForumBundle\Manager;

use Doctrine\ORM\Query;

/**
 * Topics manager
 *
 * @author Kamil Demurat
 */
class TopicManager extends BasicManager
{
    /**
     * Returns topics by forum id
     * 
     * @param int $forumId
     * 
     * @return Doctrine\ORM\Query
     */
    public function findTopicsByForum($forumId)
    {
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

    /**
     * Returns topic by post
     * 
     * @param int $postId
     * 
     * @return array
     */
    public function findOneByPost($postId)
    {
        $qb = $this->repository->createQueryBuilder('t');
        $qb->select('t')
            ->leftJoin('t.posts', 'p')
            ->where('p.id = :postId')
            ->setParameter('postId', $postId);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Returns readed topics by user
     * 
     * @param array   $topicsIds
     * @param integer $userId
     * 
     * @return array
     */
    public function readedTopics($topicsIds, $userId)
    {
        if (empty($topicsIds)) {
            return array();
        }

        $qb = $this->repository->createQueryBuilder('t');
        $qb->select('t.id')
            ->leftJoin('t.readers', 'r')
            ->where($qb->expr()->in('t.id', ':topics'))
            ->andWhere('r.id = :userId')
            ->setParameters(array(
                'topics' => $topicsIds,
                'userId' => $userId
            ));
        $query = $qb->getQuery();
        $queryResult = $query->getResult();

        $result = array_map('current', $queryResult);

        return $result;
    }

    /**
     * Looking for phrase in post name or description and topic name or description
     * 
     * @param string $phrase
     * 
     * @return Query
     */
    public function findWith($phrase)
    {
        $qb = $this->repository->createQueryBuilder('t');
        $qb->select('t', 'p', 'a')
            ->leftJoin('t.posts', 'p')
            ->leftJoin('t.author', 'a')
            ->where($qb->expr()->orX(
                $qb->expr()->like('t.name', ':phrase'),
                $qb->expr()->like('t.description', ':phrase'),
                $qb->expr()->like('p.description', ':phrase')
            ))
            ->setParameters(array(
                'phrase' => '%' . $phrase . '%',
            ));

        $query = $qb->getQuery();

        return $query;
    }
}
