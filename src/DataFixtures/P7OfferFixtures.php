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

use App\Entity\Offer;
use App\Repository\OfferTypeRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class P7OfferFixtures
 */
class P7OfferFixtures extends Fixture
{
    private $offerTypeRepository;
    private $userRepository;

    /**
     * P7OfferFixtures constructor.
     * @param OfferTypeRepository $offerTypeRepository
     * @param UserRepository      $userRepository
     */
    public function __construct(OfferTypeRepository $offerTypeRepository, UserRepository $userRepository)
    {
        $this->offerTypeRepository = $offerTypeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $offerTypes = $this->offerTypeRepository->findAll();
        $users = $this->userRepository->findAll();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $offer = new Offer();
            $offer->setTitle($faker->word);
            $offer->setNumber($faker->numberBetween(100, 4000000));
            $offer->setAttach(
                [
                $faker->file('D:\temp', 'D:\SyServer\sites\crm.bel\public\AttachOffers', true),
                $faker->file('D:\temp', 'D:\SyServer\sites\crm.bel\public\AttachOffers', true), ]
            );
            $offer->setOfferType($offerTypes[rand(0, count($offerTypes) - 1)]);
            $offer->setUser($users[rand(0, count($users) - 1)]);

            $manager->persist($offer);
        }

        $manager->flush();
    }
}
