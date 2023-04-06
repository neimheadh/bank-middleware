<?php

namespace App\Tests\Event\ORM\EntitySubscriber;

use App\Entity\Account\Account;
use App\Tests\DefaultCurrencyInitTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * DefaultJoinSetEventSubscriber test suite.
 */
class DefaultJoinSetEventSubscriberTest extends KernelTestCase
{
    use DefaultCurrencyInitTrait;

    /**
     * Test default currency is automatically set.
     *
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function testDefaultCurrencyAutomaticallySet(): void
    {
        $account = new Account();
        $account->setName(uniqid());
        $account->setCode(uniqid());

        $this->assertNull($account->getCurrency());

        $manager = static::getContainer()->get('doctrine.orm.entity_manager');
        $manager->clear();
        $manager->persist($account);
        $manager->flush();

        $this->assertNotNull($account->getCurrency());
        $this->assertTrue($account->getCurrency()->isDefault());
    }
}