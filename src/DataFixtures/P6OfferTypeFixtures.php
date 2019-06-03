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

use App\Entity\OfferType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class P6OfferTypeFixtures
 */
class P6OfferTypeFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $offerTypeDescriptions = ['От поставщиков', 'От нас'];

        foreach ($offerTypeDescriptions as $offerTypeDescription) {
            $offerType = new OfferType();
            $offerType->setDescription($offerTypeDescription);

            $manager->persist($offerType);
        }

        $manager->flush();
    }
}
