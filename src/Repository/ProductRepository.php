<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @param string $order
     * @return Product[] Returns an array of Product objects
     */

    public function findByFields(int $page, int $limit, string $order, string $orderBy)
    {
        return $this->createQueryBuilder('p')
            ->setFirstResult($page*$limit)
            ->setMaxResults($limit)
            ->orderBy('p.'.$order, $orderBy)
            ->getQuery()
            ->getResult()
        ;
    }
}
