<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\DataFixtures;

use App\Entity\Contractor;
use App\Repository\ContractorTypeRepository;
use App\Repository\CountryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class P3ContractorFixtures
 */
class P3ContractorFixtures extends Fixture
{
    private $contractorTypeRepository;
    private $countryRepository;

    /**
     * P3ContractorFixtures constructor.
     * @param ContractorTypeRepository $contractorTypeRepository
     * @param CountryRepository        $countryRepository
     */
    public function __construct(ContractorTypeRepository $contractorTypeRepository, CountryRepository $countryRepository)
    {
        $this->contractorTypeRepository = $contractorTypeRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $contractorTypes = $this->contractorTypeRepository->findAll();
        $countries = $this->countryRepository->findAll();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $contractor = new Contractor();

            $contractor->setName($faker->company);
            $contractor->setEmail([$faker->companyEmail]);
            $contractor->setPhone([$faker->phoneNumber]);
            $contractor->setContractorType($contractorTypes[rand(0, count($contractorTypes) - 1)]);
            $contractor->setAddress($faker->address);
            $contractor->setCountry($countries[rand(0, count($countries) - 1)]);

            $manager->persist($contractor);
        }

        $manager->flush();
    }
}
