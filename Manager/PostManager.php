<?php

namespace Valantir\ForumBundle\Manager;

/**
 * Posts manager
 *
 * @author Kamil Demurat
 */
class PostManager extends BasicManager
{
    /**
     * Gets last posts from forums - one on forum
     * 
     * @param array $forumsIds
     * 
     * @return array
     */
    public function getForumsLastPosts(array $forumsIds = array())
    {
        $qb = $this->repository->createQueryBuilder('p');
        $qb->select('p')
            ->leftJoin('p.author', 'u')
            ->leftJoin('p.topic', 't')
            ->leftJoin('t.forum', 'f')
            ->where($qb->expr()->in('f.id', ':forumsIds'))
            ->setParameter('forumsIds', $forumsIds)
            ->orderBy('p.createdAt', 'ASC');

        $query = $qb->getQuery();
        $posts = $query->getResult();

        if (empty($posts)) {
            return array();
        }

        $result = array();
        foreach ($posts as $post) {
            $result[$post->getTopic()->getForum()->getId()] = $post;
        }

        return $result;
    }

    /**
     * Gets last posts from topics - one on topic
     * 
     * @param array $postsIds
     * 
     * @return array
     */
    public function getTopicsLastPosts(array $postsIds = array())
    {
        $qb = $this->repository->createQueryBuilder('p');
        $qb->select('p', 't', 'u')
            ->leftJoin('p.author', 'u')
            ->leftJoin('p.topic', 't')
            ->where($qb->expr()->in('t.id', ':postsIds'))
            ->setParameter('postsIds', $postsIds)
            ->orderBy('p.createdAt', 'ASC');

        $query = $qb->getQuery();
        $posts = $query->getResult();

        if (empty($posts)) {
            return array();
        }

        $result = array();
        foreach ($posts as $post) {
            $result[$post->getTopic()->getId()] = $post;
        }

        return $result;
    }

    /**
     * Gets Query posts by topic id
     * 
     * @param int $topicId
     * 
     * @return Doctrine\ORM\Query
     */
    public function findPostsByTopic($topicId)
    {
        $qb = $this->repository->createQueryBuilder('p');

        $qb->select('p', 't', 'u')
            ->leftJoin('p.votes', 'v')
            ->leftJoin('p.author', 'u')
            ->leftJoin('p.topic', 't')
            ->where('t.id = :topicId')
            ->setParameter('topicId', $topicId)
            ->addOrderBy('p.createdAt', 'ASC');

        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Gets count of post in topic
     * 
     * @param int $topicId
     * 
     * @return int
     */
    public function countPostsInTopic($topicId)
    {
        $qb = $this->repository->createQueryBuilder('p');
        $qb->select('COUNT(p) AS quantity')
            ->leftJoin('p.topic', 't')
            ->where('t.id = :topicId')
            ->setParameter('topicId', $topicId);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }
}
