<?php

namespace AppBundle\Repository;


/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{

    public function findLastPosts(int $nbPosts)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.creationDate', 'DESC')
            ->setMaxResults($nbPosts)
            ->getQuery();

        try{
            return $query->getResult();
        } catch (\Exception $e){
            return null;
        }
    }
}