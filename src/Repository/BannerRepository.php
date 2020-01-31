<?php

namespace App\Repository;

use App\Entity\Banner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Banner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Banner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Banner[]    findAll()
 * @method Banner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Banner::class);
    }

    public function findIndexDesktopItems()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->andWhere('b.onIndex = 1')
            ->andWhere('b.onDesktop = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIndexMobileItems()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->andWhere('b.onIndex = 1')
            ->andWhere('b.onMobile = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findIndexTabletItems()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->andWhere('b.onIndex = 1')
            ->andWhere('b.onTablet = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findArticleCategoryDesktopItems()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->andWhere('b.onArticleCategory = 1')
            ->andWhere('b.onDesktop = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findArticleCategoryMobileItems()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->andWhere('b.onArticleCategory = 1')
            ->andWhere('b.onMobile = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findArticleCategoryTabletItems()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->andWhere('b.onArticleCategory = 1')
            ->andWhere('b.onTablet = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findArticleItems()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->andWhere('b.onArticle = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }
}
