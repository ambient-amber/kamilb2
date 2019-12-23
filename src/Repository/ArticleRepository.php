<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findActiveItems($languageTextId)
    {
        return $this->createQueryBuilder('article')
            ->select('article, trans')
            ->join('article.articleTranslations', 'trans')
            ->join('trans.language', 'language', Expr\Join::WITH, 'language.textId = :language_text_id')
            ->andWhere('article.pub = 1')
            ->setParameter('language_text_id', $languageTextId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUrl($languageTextId, $url)
    {
        return $this->createQueryBuilder('article')
            ->select('article, trans')
            ->join('article.articleTranslations', 'trans')
            ->join('trans.language', 'language', Expr\Join::WITH, 'language.textId = :language_text_id')
            ->andWhere('article.pub = 1')
            ->andWhere('article.url = :url')
            ->setParameter('language_text_id', $languageTextId)
            ->setParameter('url', $url)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
