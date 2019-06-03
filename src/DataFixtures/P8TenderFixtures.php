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

use App\Entity\Tender;
use App\Repository\ContractorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class P8TenderFixtures
 */
class P8TenderFixtures extends Fixture
{
    private $contractorRepository;


    /**
     * P8TenderFixtures constructor.
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

        for ($i = 0; $i < 10; $i++) {
            $tender = new Tender();
            $tender->setTitle($faker->word);
            $tender->setDescription($faker->randomLetter);
            $tender->setAttach(
                [
                    $faker->file('D:\temp', 'D:\SyServer\sites\crm.bel\public\AttachTenders', true),
                    $faker->file('D:\temp', 'D:\SyServer\sites\crm.bel\public\AttachTenders', true), ]
            );
            $tender->setContractor($contractors[rand(0, count($contractors) - 1)]);
            $tender->setShortDescription($faker->jobTitle);
            $tender->setOpenedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
            $tender->setStartAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
            $tender->setEndAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
            $tender->setSentAt(new \DateTime(sprintf('-%d days', rand(1, 100))));

            $manager->persist($tender);
        }

        $manager->flush();
    }
}
