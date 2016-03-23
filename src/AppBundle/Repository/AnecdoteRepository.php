<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AnecdoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnecdoteRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll($filters, $page, $nbPerPage)
    {
        $qb = $this->createQueryBuilder('a')
          ->join('a.category', 'c')
          ->addSelect('c')
          ->orderBy('a.id', 'ASC');

        // TODO: Add search form
        if ($filters != null) {
            if (array_key_exists(
                'category',
                $filters
              ) && $filters['category'] != ''
            ) {
                $qb->andWhere('c.slug like :category')
                  ->setParameter('category', '%'.$filters['category'].'%');
            }
        }

        // LIMIT and OFFSET management => pagination
        $qb->setFirstResult(($page - 1) * $nbPerPage)
          ->setMaxResults($nbPerPage);

        return new Paginator($qb->getQuery(), true);
    }

    public function queryLatest()
    {
        return $this->createQueryBuilder('a')
          ->join('a.category', 'c')
          ->addSelect('c')
          ->orderBy('a.id', 'ASC')
          ->getQuery();
    }
}
