<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleCategory;
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

    public function findActiveByCategory(ArticleCategory $category, $locale)
    {
        return $this->createQueryBuilder('article')
            ->select('article, trans')
            ->join('article.articleTranslations', 'trans')
            ->join('trans.language', 'language', Expr\Join::WITH, 'language.textId = :language_text_id')
            ->andWhere('article.category = :category')
            ->andWhere('article.pub = 1')
            ->setParameter('language_text_id', $locale)
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findActiveByUrl($languageTextId, $url)
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
            ->getOneOrNullResult()
        ;
    }

    public function findBySource($source)
    {
        return $this->createQueryBuilder('article')
            ->andWhere('article.source = :source')
            ->setParameter('source', $source)
            ->getQuery()
            ->getResult()
        ;
    }

    public function increaseViewsCount(Article $article)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE App\Entity\Article a
            SET a.viewsCount = a.viewsCount + 1
            WHERE a.id = :article_id
        ')
        ->setParameter('article_id', $article->getId());

        $query->execute();
    }
}
