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

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class P2CountryFixtures
 */
class P2CountryFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $countryNames = [
            'Беларусь',
            'Россия',
            'Украина',
            'Молдова',
            'Узбекистан',
            'Казахстан',
            'Латвия',
            'Польша',
            'Германия',
            'Австрия',
            'Италия',
            'Литва',
            'Франция',
            'Китай',
            'Сербия',
        ];
        sort($countryNames);

        foreach ($countryNames as $countryName) {
            $country = new Country();
            $country->setName($countryName);

            $manager->persist($country);
        }

        $manager->flush();
    }
}
