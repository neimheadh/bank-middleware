<?php

namespace App\Tests\Import\Writer;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Entity\Currency\Currency;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Writer\OrmWriter;
use App\Repository\Currency\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * OrmWriter test suite.
 */
class OrmWriterTest extends KernelTestCase
{

    /**
     * Doctrine ORM manager.
     *
     * @var EntityManagerInterface|null
     */
    private ?EntityManagerInterface $manager = null;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->manager = static::getContainer()->get(
            'doctrine.orm.entity_manager'
        );

        /** @var CurrencyRepository $currencyRepository */
        $currencyRepository = $this->manager->getRepository(Currency::class);

        if ($currencyRepository->findDefault() === null) {
            $currency = new Currency();
            $currency->setDefault(true);
            $currency->setCode('EUR');
            $this->manager->persist($currency);
            $this->manager->flush();
        }
    }

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
        $writer->write([$account, $t1, $t2],
            [OrmWriter::OPTION_BULK_PERSIST => true]);
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