<?php

namespace App\Tests\Event\ORM\EntitySubscriber;

use App\Entity\Account\Account;
use App\Tests\DefaultCurrencyInitTrait;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * DatedEntityDateSetEventSubscriber test suite.
 */
class DatedEntityDateSetEventSubscriber extends KernelTestCase
{
    use DefaultCurrencyInitTrait;

    /**
     * Test date are automatically set.
     *
     * @test
     *
     * @return void
     * @throws \Exception
     */
    public function testDateSet(): void
    {
        $now = new DateTime();
        $account = new Account();
        $account->setName(uniqid());
        $account->setCode(uniqid());

        $this->assertNull($account->getUpdatedAt());
        $this->assertNull($account->getCreatedAt());

        $manager = static::getContainer()->get('doctrine.orm.entity_manager');
        $manager->persist($account);
        $manager->flush();

        $this->assertNotNull($createdAt = $account->getCreatedAt());
        $this->assertGreaterThanOrEqual($now, $createdAt);
        $this->assertNull($account->getUpdatedAt());

        $account->setBalance(10);
        $manager->persist($account);
        $manager->flush();

        $this->assertEquals($createdAt, $account->getCreatedAt());
        $this->assertNotNull($account->getUpdatedAt());
        $this->assertGreaterThanOrEqual($now, $account->getUpdatedAt());
    }
}