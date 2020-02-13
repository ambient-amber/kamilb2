<?php

namespace App\Repository;

use App\Entity\Banner;
use Doctrine\ORM\QueryBuilder;
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

    public function findFrontendBanners(string $device, string $pageType)
    {
        $qb = $this->createQueryBuilder('b')
            ->andWhere('b.pub = 1')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(3);

        $this->addDeviceFilter($qb, $device);
        $this->addPageTypeFilter($qb, $pageType);

        $query = $qb->getQuery();

        return $query->execute();
    }

    private function addDeviceFilter(QueryBuilder $qb, $device)
    {
        $clause = false;

        switch ($device) {
            case 'desktop':
                $clause = 'b.onDesktop = 1';
                break;
            case 'tablet':
                $clause = 'b.onTablet = 1';
                break;
            case 'mobile':
                $clause = 'b.onMobile = 1';
                break;
        }

        return $qb->andWhere($clause);
    }

    private function addPageTypeFilter(QueryBuilder $qb, $pageType)
    {
        $clause = false;

        switch ($pageType) {
            case 'index':
                $clause = 'b.onIndex = 1';
                break;
            case 'article_category':
                $clause = 'b.onArticleCategory = 1';
                break;
            case 'article':
                $clause = 'b.onArticle = 1';
                break;
        }

        return $qb->andWhere($clause);
    }
}
