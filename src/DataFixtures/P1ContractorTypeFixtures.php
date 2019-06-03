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

use App\Entity\ContractorType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class 1ContractorTypeFixtures
 */
class P1ContractorTypeFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $contractorTypeDescriptions = ['Поставщики', 'Клиенты', 'Наша компания'];

        foreach ($contractorTypeDescriptions as $contractorTypeDescription) {
            $contractorType = new ContractorType();
            $contractorType->setDescription($contractorTypeDescription);

            $manager->persist($contractorType);
        }

        $manager->flush();
    }
}
