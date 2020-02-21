<?php

namespace App\Repository;

use App\Entity\PopularShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PopularShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method PopularShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method PopularShop[]    findAll()
 * @method PopularShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PopularShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PopularShop::class);
    }

    public function findIndexItems()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.pub = 1')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return PopularShop[] Returns an array of PopularShop objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PopularShop
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
