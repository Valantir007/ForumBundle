<?php

namespace Valantir\ForumBundle\Manager;

use Valantir\ForumBundle\Entity\Forum;

/**
 * Forums manager
 *
 * @author Kamil Demurat
 */
class ForumManager extends BasicManager
{
    /**
     * Gets all forums by parent
     * 
     * @param int|null $parent
     * 
     * @return Doctrine\ORM\Query
     */
    public function findForums($parentSlug = null)
    {
        $qb = $this->repository->createQueryBuilder('f');
        $qb->select('f', 't', 'p.createdAt')
            ->leftJoin('f.topics', 't')
            ->leftJoin('t.posts', 'p')
            ->leftJoin('f.parent', 'fp')
            ->leftJoin('p.author', 'u');

        if ($parentSlug) {
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
     * Counts all topics and posts in forums by slug
     * 
     * @param string $slug
     * 
     * @return array
     */
    public function countTopicsAndPosts($slug)
    {
        $result = array();
        $level = $this->getLevelByForumSlug($slug);
        $qb = $this->repository->createQueryBuilder('f');
        $qb->select('f.id, fp.id AS parentId, COUNT(DISTINCT t) as topicsCount', 'COUNT(DISTINCT p) as postsCount')
            ->leftJoin('f.topics', 't')
            ->leftJoin('f.parent', 'fp')
            ->leftJoin('t.posts', 'p')
            ->where($qb->expr()->orX(
                $qb->expr()->eq('f.level', ':firstLevel'),
                $qb->expr()->eq('f.level', ':secondLevel')
            ))
            ->setParameters(array(
                'firstLevel' => $level,
                'secondLevel' => $level + 1
            ));

        $qb->groupBy('f.id')
            ->orderBy('f.id');

        $query = $qb->getQuery();
        $tempResult = $query->getResult();
        foreach ($tempResult as $row) {
            $result[$row['id']] = $row;
            if ($row['parentId'] && isset($result[$row['parentId']])) {
                $result[$row['parentId']]['postsCount'] += $row['postsCount'];
                $result[$row['parentId']]['topicsCount'] += $row['topicsCount'];
            }
        }

        return $result;
    }

    /**
     * Gets level by slug
     * 
     * @param string $slug
     * 
     * @return integer
     */
    public function getLevelByForumSlug($slug)
    {
        $qb = $this->repository->createQueryBuilder('f');
        $qb->select('f.level')
            ->where('f.slug = :slug')
            ->setParameter('slug', $slug);

        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();

        return (int)$result;
    }
}
