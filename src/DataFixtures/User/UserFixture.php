<?php

namespace App\DataFixtures\User;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * User fixture loader.
 */
class UserFixture extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@example.com');
        $user->setEnabled(true);
        $user->setPlainPassword('test');
        $manager->persist($user);

        $manager->flush();
    }

}