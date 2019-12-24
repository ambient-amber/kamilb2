<?php

namespace App\Repository;

use App\Entity\ContentBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContentBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentBlock[]    findAll()
 * @method ContentBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentBlockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContentBlock::class);
    }
}
