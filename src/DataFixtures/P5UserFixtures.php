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

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class P5UserFixtures
 */
class P5UserFixtures extends Fixture
{
    private $passwordEncoder;


    /**
     * P5UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $usersInfo = [
            ['User 1', 'User 1 Family name', 'ad@ad.ad', '12'],
            ['User2', 'User 2 Family name', $faker->email, '12'],
        ];

        $i = 0;
        foreach ($usersInfo as $userInfo) {
            $user = new User();
            $user->setName($userInfo[0]);
            $user->setSurname($userInfo[1]);
            $user->setEmail($userInfo[2]);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userInfo[3]));
            $user->setPhone($faker->phoneNumber);
            if (0 === $i) {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $manager->persist($user);

            $i++;
        }

        $manager->flush();
    }
}
