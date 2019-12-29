<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findFooterItems($languageTextId)
    {
        return $this->createQueryBuilder('page')
            ->select('page, trans')
            ->join('page.pageTranslations', 'trans')
            ->join('trans.language', 'language', Expr\Join::WITH, 'language.textId = :language_text_id')
            ->andWhere('page.pub = 1')
            ->andWhere('page.showInFooter = 1')
            ->setParameter('language_text_id', $languageTextId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByUrl($languageTextId, $url)
    {
        return $this->createQueryBuilder('page')
            ->select('page, trans')
            ->join('page.pageTranslations', 'trans')
            ->join('trans.language', 'language', Expr\Join::WITH, 'language.textId = :language_text_id')
            ->andWhere('page.pub = 1')
            ->andWhere('page.url = :url')
            ->setParameter('language_text_id', $languageTextId)
            ->setParameter('url', $url)
            ->getQuery()
            ->getResult()
            ;
    }
}
