<?php

namespace App\Repository;

use App\Entity\ContractorType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContractorType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractorType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractorType[]    findAll()
 * @method ContractorType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractorTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContractorType::class);
    }

    public function getCustomerType()
    {
        return $this->findOneBy([
            'description' => 'Клиенты'
        ]);
    }

    public function getSupplierType()
    {
        return $this->findOneBy([
            'description' => 'Поставщики'
        ]);
    }

    // /**
    //  * @return ContractorType[] Returns an array of ContractorType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContractorType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
