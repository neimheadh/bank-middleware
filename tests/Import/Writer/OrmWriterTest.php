<?php

namespace App\Tests\Import\Writer;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Writer\OrmWriter;
use App\Tests\DefaultCurrencyInitTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * OrmWriter test suite.
 */
class OrmWriterTest extends KernelTestCase
{

    use DefaultCurrencyInitTrait;

    /**
     * Test single entity writer.
     *
     * @test
     * @functionnal
     *
     * @return void
     * @throws Throwable
     */
    public function testSingleEntity(): void
    {
        $name = uniqid();

        $account = new Account();
        $account->setCode(uniqid());
        $account->setName($name);

        $writer = new OrmWriter($this->manager);
        $writer->write($account);

        $this->assertNotNull($account->getId());
        $this->assertEquals($name, $account->getName());
    }

    /**
     * Test with multiple entities.
     *
     * @test
     * @functionnal
     *
     * @return void
     * @throws Throwable
     */
    public function testMultipleEntities(): void
    {
        $account = new Account();
        $account->setName('Test account 1');
        $account->setCode(uniqid());

        $t1 = new Transaction();
        $t2 = new Transaction();

        $t1->setName('Transaction 1');
        $t2->setName('Transaction 2');
        $t1->setBalance(0);
        $t2->setBalance(0);

        $t1->setAccount($account);
        $t2->setAccount($account);

        $writer = new OrmWriter($this->manager);
        $writer->write([$account, $t1, $t2]);
        $this->assertNotNull($account->getId());
        $this->assertNotNull($t1->getId());
        $this->assertNotNull($t2->getId());
    }

    /**
     * Test exceptions.
     *
     * @test
     * @functionnal
     *
     * @return void
     */
    public function testExceptions(): void
    {
        $e = null;
        try {
            (new OrmWriter($this->manager))->write('Test');
        } catch (InputNotSupportedException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Given input of type "string" not supported by "App\Import\Writer\OrmWriter".',
            $e->getMessage()
        );
    }

}