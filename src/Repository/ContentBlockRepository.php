<?php

namespace App\Repository;

use App\Entity\ContentBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContentBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentBlock[]    findAll()
 * @method ContentBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentBlock::class);
    }
}
