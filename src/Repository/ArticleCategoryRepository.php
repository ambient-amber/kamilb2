<?php

namespace App\Repository;

use App\Entity\ArticleCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @method ArticleCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleCategory[]    findAll()
 * @method ArticleCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleCategory::class);
    }

    public function findActive($locale)
    {
        return $this->createQueryBuilder('a_category')
            ->select('a_category, trans')
            ->join('a_category.articleCategoryTranslations', 'trans')
            ->join('trans.language', 'language', Expr\Join::WITH, 'language.textId = :language_text_id')
            ->andWhere('a_category.pub = 1')
            ->setParameter('language_text_id', $locale)
            ->orderBy('a_category.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByUrl($url, $locale)
    {
        $item = $this->createQueryBuilder('a_category')
            ->select('a_category, trans')
            ->join('a_category.articleCategoryTranslations', 'trans')
            ->join('trans.language', 'language')
            ->andWhere('a_category.url = :url')
            ->andWhere('a_category.pub = 1')
            ->setParameter('url', $url)
            ->orderBy('a_category.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();

        foreach ($item->getArticleCategoryTranslations() as $translation) {
            if ($translation->getLanguage()->getTextId() == $locale) {
                $item->setRelevantTranslation($translation);
                break;
            }
        }

        return $item;
    }

    public function findActiveByUrlScalar($url)
    {
        return $this->createQueryBuilder('a_category')
            ->select('a_category, trans, language')
            ->join('a_category.articleCategoryTranslations', 'trans')
            ->join('trans.language', 'language')
            ->andWhere('a_category.url = :url')
            ->andWhere('a_category.pub = 1')
            ->setParameter('url', $url)
            ->orderBy('a_category.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
