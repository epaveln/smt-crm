<?php

namespace App\Repository;

use App\Entity\AdviceSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdviceSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdviceSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdviceSettings[]    findAll()
 * @method AdviceSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdviceSettingsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdviceSettings::class);
    }

    // /**
    //  * @return AdviceSettings[] Returns an array of AdviceSettings objects
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
    public function findOneBySomeField($value): ?AdviceSettings
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
