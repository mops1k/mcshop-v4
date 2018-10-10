<?php

namespace McShop\ShoppingCartBundle\Repository;

use McShop\ShoppingCartBundle\Entity\ShoppingCartItem;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * BuyHistoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BuyHistoryRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return Pagerfanta
     */
    public function findAllAsPagination()
    {
        $qb = $this->createQueryBuilder('bh');
        $qb
            ->select('bh, u')
            ->leftJoin('bh.user', 'u')
        ;

        $adapter = new DoctrineORMAdapter($qb);
        $pagination = new Pagerfanta($adapter);

        return $pagination;
    }

    public function getTotalIncome()
    {
        $qb = $this->createQueryBuilder('bh');
        $entries = $qb->select('bh')->getQuery()->execute();

        $total = 0;
        foreach ($entries as $entry) {
            /** @var ShoppingCartItem $item */
            $item = $entry->getItem();
            $total += $item->getPrice() - $item->getPrice() / 100 * $item->getSale();
        }

        return $total;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalCount()
    {
        $qb = $this->createQueryBuilder('bh');

        return $qb->select('COUNT(bh)')->getQuery()->getSingleScalarResult();
    }
}
