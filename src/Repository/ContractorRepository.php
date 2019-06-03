<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Repository;

use App\Entity\Contractor;
use App\Entity\ContractorType;
use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contractor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contractor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contractor[]    findAll()
 * @method Contractor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractorRepository extends ServiceEntityRepository
{
    private $contractorTypeRepository;

    /**
     * ContractorRepository constructor.
     * @param RegistryInterface        $registry
     * @param ContractorTypeRepository $contractorTypeRepository
     */
    public function __construct(RegistryInterface $registry, ContractorTypeRepository $contractorTypeRepository)
    {
        parent::__construct($registry, Contractor::class);

        $this->contractorTypeRepository = $contractorTypeRepository;
    }

    /**
     * @param string $query
     * @param object $contractorType
     * @param int    $limit
     *
     * @return       Contractor[]
     */
    public function findAllMatching(string $query, ContractorType $contractorType, int $limit = 5)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :query')
            ->andWhere('c.contractorType = :contractorType')
            ->setParameter('contractorType', $contractorType)
            ->setParameter('query', '%'.$query.'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Contractor[]
     */
    public function findAllCustomers()
    {
        return $this->findBy([
            'contractorType' => $this->contractorTypeRepository->findOneBy([
                'description' => 'Клиенты',
            ]),
        ]);
    }

    /**
     * @param ContractorType $contractorType
     *
     * @return mixed
     */
    public function findAllContractorsByType(ContractorType $contractorType)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.contractorType = :contractorType')
            ->setParameter('contractorType', $contractorType)
            ->orderBy('c.name', 'ASC')
            //->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Contractor[]
     */
    public function findAllContractorsByTypeWithQB(ContractorType $contractorType): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.contractorType = :contractorType')
            ->setParameter('contractorType', $contractorType)
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(30);
    }

    public function findAllCustomersByCountry(Country $country)
    {
        return $this->findBy([
            'contractorType' => $this->contractorTypeRepository->findOneBy([
                'description' => 'Клиенты',
            ]),
            'country' => $country,
        ]);
    }

    public function findAllSuppliersByCountry(Country $country)
    {
        return $this->findBy([
            'contractorType' => $this->contractorTypeRepository->findOneBy([
                'description' => 'Поставщики',
            ]),
            'country' => $country,
        ]);
    }
}
