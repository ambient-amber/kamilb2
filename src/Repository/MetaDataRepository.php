<?php

namespace App\Repository;

use App\Entity\MetaData;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MetaData|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetaData|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetaData[]    findAll()
 * @method MetaData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetaDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MetaData::class);
    }

    public function findActiveByUrl($uri, $languageTextId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare('
            SELECT
                meta.title,
                meta.description,
                meta.key_words,
                meta.is_template,
                if(
                    meta.is_regexp,
                    :url REGEXP meta.url,
                    :url = meta.url
                ) as check_url
            FROM
                meta_data meta
                JOIN language on language.id = meta.language_id
            WHERE
                meta.pub = 1
                AND language.text_id = :language_text_id
            HAVING
                check_url
            ORDER BY sort DESC
            LIMIT 1
        ');

        $stmt->execute([
            'url' => $uri,
            'language_text_id' => $languageTextId,
        ]);

        return $stmt->fetch();
    }
}
