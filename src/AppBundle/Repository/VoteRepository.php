<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 20/08/17
 * Time: 15:14.
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class VoteRepository extends EntityRepository
{
    public function updateCount(string $ref, int $refId)
    {
        $votes = $this->createQueryBuilder('v')
            ->select('COUNT(v)')
            ->groupBy('v.value')
            ->where('v.ref = :ref')
            ->andWhere('v.refId = :refId')
            ->setParameters(['ref' => $ref, 'refId' => $refId])
            ->getQuery()
            ->getScalarResult();
    }
}
