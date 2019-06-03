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

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class P0RoleFixtures
 */
class P0RoleFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleNames = ['ROLE_ADMIN', 'ROLE_USER'];

        foreach ($roleNames as $roleName) {
            $role = new Role();
            $role->setName($roleName);

            $manager->persist($role);
        }

        $manager->flush();
    }
}
