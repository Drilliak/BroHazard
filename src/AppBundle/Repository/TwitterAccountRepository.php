<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 17/08/17
 * Time: 19:17
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TwitterAccountRepository extends EntityRepository
{

    public function findAllName(){
        return $this->createQueryBuilder('t')
            ->select('t.username')
            ->getQuery()
            ->getArrayResult();
    }
}