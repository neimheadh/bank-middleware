<?php

namespace App\Tests\Event\ORM\EntityListener;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * AccountEntityListener test suite.
 */
class AccountEntityListenerTest extends KernelTestCase
{

    /**
     * Test loaded account have future balance recalculated.
     *
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function testLoadedAccountFutureBalanceCalculated(): void
    {
        $account = new Account();
        $transaction = new Transaction();

        $account->setName(uniqid());
        $account->setCode(uniqid());
        $account->addTransaction($transaction);

        $transaction->setName(uniqid());
        $transaction->setBalance(10);

        $manager = static::getContainer()->get('doctrine.orm.entity_manager');
        $manager->persist($account);
        $manager->flush();
        $manager->clear();
        $this->assertNotNull($account->getId());

        /** @var Account $account */
        $account = $manager->find(Account::class, $account->getId());
        $this->assertEquals(10, $account->getFutureBalance());
        $this->assertEquals(0, $account->getBalance());
    }
}