<?php

namespace App\Repository;

use App\Entity\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Language|null find($id, $lockMode = null, $lockVersion = null)
 * @method Language|null findOneBy(array $criteria, array $orderBy = null)
 * @method Language[]    findAll()
 * @method Language[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }

    public function findByTextId($textId)
    {
        return $this->createQueryBuilder('language')
            ->andWhere('language.textId = :text_id')
            ->setParameter('text_id', $textId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
