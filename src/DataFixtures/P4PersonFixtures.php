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

use App\Entity\Person;
use App\Repository\ContractorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class P4PersonFixtures
 */
class P4PersonFixtures extends Fixture
{
    private $contractorRepository;

    /**
     * P4PersonFixtures constructor.
     * @param ContractorRepository $contractorRepository
     */
    public function __construct(ContractorRepository $contractorRepository)
    {
        $this->contractorRepository = $contractorRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $contractors = $this->contractorRepository->findAll();
        $faker = Factory::create();

        for ($i = 0; $i < count($contractors); $i++) {
            $person = new Person();

            $person->setPhone([$faker->phoneNumber, $faker->phoneNumber]);
            $person->setEmail([$faker->email]);
            $person->setName($faker->firstName.' '.$faker->lastName);
            $person->setPosition($faker->jobTitle);
            $person->setSkypeId('sk_'.$faker->word);
            $person->setTelegram('te_'.$faker->word);
            $person->setContractor($contractors[$i]);

            $manager->persist($person);
        }

        $manager->flush();
    }
}
