<?php

namespace App\Tests\Event\ORM\EntityListener;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Tests\DefaultCurrencyInitTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * TransactionEntityListener test suite.
 */
class TransactionEntityListenerTest extends KernelTestCase
{
    use DefaultCurrencyInitTrait;

    /**
     * Test transaction creation lifecycle events.
     *
     * @test
     *
     * @return void
     * @throws \Exception
     */
    public function testTransactionCreation(): void
    {
        /** @var EntityManagerInterface $manager */
        $manager = static::getContainer()->get('doctrine.orm.entity_manager');

        $account = new Account();
        $account->setCode(uniqid());
        $account->setName('Account ' . self::class);
        $account->setBalance(125.99);
        $manager->persist($account);
        $manager->flush();

        $transaction = new Transaction();
        $transaction->setBalance(14.3);
        $transaction->setName('Test transaction create - ' . self::class);
        $transaction->setAccount($account);
        $manager->persist($transaction);
        $manager->flush();

        $this->assertSame($account->getCurrency(), $transaction->getCurrency());
        $this->assertEquals(125.99, $account->getBalance());

        $transaction->setProcessed(true);
        $manager->persist($transaction);
        $manager->flush();
        $this->assertEquals(140.29, $account->getBalance());
    }

    /**
     * Test transaction remove lifecycle events.
     *
     * @test
     *
     * @return void
     * @throws \Exception
     */
    public function testTransactionRemove(): void
    {
        /** @var EntityManagerInterface $manager */
        $manager = static::getContainer()->get('doctrine.orm.entity_manager');

        $account = new Account();
        $account->setCode(uniqid());
        $account->setName('Account ' . self::class);
        $account->setBalance(125.99);
        $manager->persist($account);
        $manager->flush();

        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setName('Test transaction create - ' . self::class);
        $transaction->setBalance(-127.99);
        $transaction->setProcessed(true);
        $manager->persist($transaction);
        $manager->flush();
        $this->assertEquals(-2.0, $account->getBalance());

        $manager->remove($transaction);
        $manager->flush();
        $this->assertEquals(125.99, $account->getBalance());
    }

    /**
     * Test transaction remove lifecycle events.
     *
     * @test
     *
     * @return void
     * @throws \Exception
     */
    public function testTransactionUpdate(): void
    {
        /** @var EntityManagerInterface $manager */
        $manager = static::getContainer()->get('doctrine.orm.entity_manager');

        $account = new Account();
        $account->setCode(uniqid());
        $account->setName('Account ' . self::class);
        $account->setBalance(125.99);
        $manager->persist($account);
        $manager->flush();
        $this->assertEquals(125.99, $account->getFutureBalance());

        $transaction = new Transaction();
        $transaction->setAccount($account);
        $transaction->setName('Test transaction create - ' . self::class);
        $transaction->setBalance(-127.99);
        $transaction->setProcessed(true);
        $manager->persist($transaction);
        $manager->flush();
        $this->assertEquals(-2.0, $account->getBalance());
        $this->assertEquals(-2.0, $account->getFutureBalance());

        $transaction->setProcessed(false);
        $manager->persist($transaction);
        $manager->flush();
        $this->assertEquals(125.99, $account->getBalance());
        $this->assertEquals(-2.0, $account->getFutureBalance());

        $transaction->setBalance(10);
        $transaction->setProcessed(true);
        $manager->persist($transaction);
        $manager->flush();
        $this->assertEquals(135.99, $account->getBalance());
        $this->assertEquals(135.99, $account->getFutureBalance());

        $transaction->setBalance(-10);
        $manager->persist($transaction);
        $manager->flush();
        // We force account future balance recalculation.
        $manager->persist($account);
        $manager->flush();
        $this->assertEquals(115.99, $account->getBalance());
        $this->assertEquals(115.99, $account->getFutureBalance());

        $account2 = new Account();
        $account2->setCode(uniqid());
        $account2->setName('Account 2 ' . self::class);
        $manager->persist($account);
        $manager->flush();

        $transaction->setAccount($account2);
        $manager->persist($transaction);
        $manager->flush();
        $this->assertEquals(125.99, $account->getBalance());
        $this->assertEquals(125.99, $account->getFutureBalance());
        $this->assertEquals(-10, $account2->getBalance());
        $this->assertEquals(-10, $account2->getBalance());

        $transaction->setCurrency($this->usd);
        $manager->persist($transaction);
        $manager->flush();
        // We force account future balance recalculation.
        $manager->persist($account2);
        $manager->flush();
        $this->assertEquals(-7.69, $account2->getBalance());
        $this->assertEquals(-7.69, $account2->getFutureBalance());
    }
}