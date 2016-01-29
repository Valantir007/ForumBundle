<?php

namespace Valantir\ForumBundle\Manager;

use Valantir\ForumBundle\Entity\PostVote;

/**
 * Posts votes manager
 *
 * @author Kamil Demurat
 */
class PostVoteManager extends BasicManager
{
    /**
     * Gets post vote by user id and post id
     * 
     * @param integer $postId
     * @param integer $userId
     * 
     * @return PostVote|null
     */
    public function findVoteForPost($postId, $userId)
    {
        $qb = $this->repository->createQueryBuilder('pv');
        $qb->select('pv')
            ->leftJoin('pv.user', 'u')
            ->leftJoin('pv.post', 'p')
            ->where('pv.post = :postId')
            ->andWhere('pv.user = :userId')
            ->setParameters(array(
                'postId' => $postId,
                'userId' => $userId,
            ));

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Gets votes of posts grouped by kind
     * 
     * @param array $postsIds
     * 
     * @return array
     */
    public function getVotesOfPosts(array $postsIds = array())
    {
        $finalResult = array();
        if (empty($postsIds)) {
            return $finalResult;
        }

        $qb = $this->repository->createQueryBuilder('pv');
        $qb->select('p.id, pv.kind, COUNT(IDENTITY(pv)) AS quantity')
            ->leftJoin('pv.post', 'p')
            ->where($qb->expr()->in('pv.post',':postsIds'))
            ->setParameters(array(
                'postsIds' => $postsIds
            ))
            ->groupBy('pv.kind')
            ->addGroupBy('pv.post');

        $query = $qb->getQuery();
        $result = $query->getResult();
        if (empty($result)) {
            return $finalResult;
        }

        foreach ($result as $row) {
            if (!isset($finalResult[$row['id']])) {
                $finalResult[$row['id']] = array('up' => 0, 'down' => 0);
            }

            $finalResult[$row['id']][($row['kind']) ? 'up' : 'down'] += $row['quantity'];
        }

        return $finalResult;
    }
}
