<?php

namespace App\Tests\Import;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Entity\ThirdParty\ThirdParty;
use App\Import\Monitoring\Event\ProgressAdvanceEvent;
use App\Import\Monitoring\Event\ProgressFinishEvent;
use App\Import\Monitoring\Event\ProgressStartEvent;
use App\Import\Monitoring\EventProgress;
use App\Import\Processor\DataMapProcessor;
use App\Import\Reader\CsvFileReader;
use App\Import\Writer\OrmWriter;
use App\Tests\DefaultCurrencyInitTrait;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Iterator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

/**
 * Full import test case.
 */
class FullImportTest extends KernelTestCase
{
    use DefaultCurrencyInitTrait {
        setUp as private initCurrency;
    }

    /**
     * Reading advance count.
     *
     * @var int
     */
    private int $advance = 0;

    /**
     * Event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * Reading finish count.
     *
     * @var int
     */
    private int $finish = 0;

    /**
     * Reading start count.
     *
     * @var int
     */
    private int $start = 0;

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->initCurrency();

        $this->dispatcher = static::getContainer()->get('event_dispatcher');

        $this->start = 0;
        $this->finish = 0;
        $this->advance = 0;
        $this->dispatcher->addListener(
            ProgressAdvanceEvent::class,
            fn() => $this->advance++
        );
        $this->dispatcher->addListener(
            ProgressFinishEvent::class,
            fn() => $this->finish++
        );
        $this->dispatcher->addListener(
            ProgressStartEvent::class,
            fn() => $this->start++
        );
    }

    /**
     * Test full import.
     *
     * @test
     * @functionnal
     *
     * @return void
     * @throws Throwable
     */
    public function testFullImport(): void
    {
        $lines = $this->read();
        $this->process($lines);

        // Because of the iteration system, limiting memory usage, iteration
        // rewinding makes new transaction object generation added by the
        // chained setAccount() -> addTransaction() to the ORM loaded account.
        // So we have to re-generate entities list before send it to the writer.
        $lines = $this->getReadList();
        $entities = $this->getProcessList($lines, uniqid());
        $this->write($entities);
    }

    /**
     * Get process list.
     *
     * @param iterable $lines       Reader output.
     * @param string   $accountCode Account code.
     *
     * @return iterable
     * @throws Throwable
     */
    private function getProcessList(iterable $lines, string $accountCode): iterable
    {
        $processor = new DataMapProcessor(static::getContainer());
        $yaml = str_replace(
            '{{account.code}}',
            $accountCode,
            file_get_contents(
                __DIR__ . '/../Resources/import/bp.processor.config.yaml'
            )
        );
        $processorOptions = Yaml::parse($yaml);

        return $processor->process($lines, $processorOptions);
    }

    /**
     * Get read list.
     *
     * @return iterable
     * @throws Throwable
     */
    private function getReadList(): iterable
    {
        $reader = new CsvFileReader();
        $readerOptions = Yaml::parseFile(
            __DIR__ . '/../Resources/import/bp.reader.config.yaml'
        );
        $readerOptions[CsvFileReader::OPTION_PROGRESS_BAR] = new EventProgress(
            $this->dispatcher
        );

        return $reader->read(
            __DIR__ . '/../Resources/import/bp.csv',
            $readerOptions
        );
    }

    /**
     * Execute & test processor.
     *
     * @param iterable $lines Reader output.
     *
     * @return iterable
     * @throws Throwable
     *
     */
    private function process(iterable $lines): iterable
    {
        $accountCode = uniqid();
        $entityList = $this->getProcessList($lines, $accountCode);
        $this->assertCount(8, $entityList);
        $this->assertInstanceOf(Iterator::class, $entityList);

        $entities = $entityList->current();
        $this->assertIsArray($entities);
        $this->assertCount(3, $entities);

        $account = $entities[0];
        $thirdParty = $entities[1];
        $transaction = $entities[2];
        $this->assertInstanceOf(Account::class, $account);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('Test account', $account->getName());
        $this->assertEquals('EUR', $account->getCurrency()?->getCode());
        $this->assertEquals($accountCode, $account->getCode());
        $this->assertNull($account->getId());
        $this->assertSame($account, $transaction->getAccount());
        $this->assertEquals('SNCF INTERNET PARIS 10', $transaction->getName());
        $this->assertEquals('EUR', $transaction->getCurrency()?->getCode());
        $this->assertSame(62.0, $transaction->getBalance());
        $this->assertEquals('SNCF', $thirdParty->getName());
        $this->assertSame(
            '07/02/2023',
            $transaction->getCreatedAt()?->format('d/m/Y')
        );
        $this->assertNull($transaction->getId());

        $entityList->next();
        $entities = $entityList->current();
        $this->assertCount(3, $entities);
        $transaction = $entities[2];
        $this->assertSame($account, $entities[0]);
        $this->assertSame($account, $transaction->getAccount());
        $this->assertEquals('Cbp France', $transaction->getName());
        $this->assertEquals('EUR', $transaction->getCurrency()?->getCode());
        $this->assertSame(
            '05/12/2022',
            $transaction->getCreatedAt()?->format('d/m/Y')
        );
        $this->assertSame(-37.46, $transaction->getBalance());

        $this->assertNull($transaction->getId());

        $this->assertEquals(1, $entityList->key());
        $this->assertEquals(1, $lines->key());

        $entityList->rewind();
        $this->assertEquals(0, $entityList->key());
        $this->assertEquals(0, $lines->key());
        return $entityList;
    }

    /**
     * Execute & test reader.
     *
     * @return iterable
     * @throws Throwable
     */
    private function read(): iterable
    {
        $lines = $this->getReadList();
        $this->assertEquals(1, $this->start);
        $this->assertEquals(1, $this->advance);
        $this->assertEquals(0, $this->finish);
        $this->assertCount(8, $lines);
        $this->assertEquals(
            [
                'Date de comptabilisation',
                'Libelle simplifie',
                'Libelle operation',
                'Reference',
                'Informations complementaires',
                'Type operation',
                'Categorie',
                'Sous categorie',
                'Debit',
                'Credit',
                'Date operation',
                'Date de valeur',
                'Pointage operation',
            ],
            array_keys($lines->current()),
        );
        $this->assertEquals(
            [
                '07/02/2023',
                'SNCF',
                'SNCF INTERNET PARIS 10',
                '1RI8LIG',
                '060223 CB*********-',
                'Carte bancaire',
                'Transports',
                'Trains, avions et ferrys',
                '',
                '+62,00',
                '07/02/2023',
                '07/02/2023',
                '0',
            ],
            array_values($lines->current())
        );
        $lines->next();
        $this->assertEquals(1, $this->start);
        $this->assertEquals(2, $this->advance);
        $this->assertEquals(0, $this->finish);
        $this->assertEquals(
            [
                'Date de comptabilisation',
                'Libelle simplifie',
                'Libelle operation',
                'Reference',
                'Informations complementaires',
                'Type operation',
                'Categorie',
                'Sous categorie',
                'Debit',
                'Credit',
                'Date operation',
                'Date de valeur',
                'Pointage operation',
            ],
            array_keys($lines->current()),
        );
        $this->assertEquals(
            [
                '05/12/2022',
                'CBP',
                'Cbp France',
                '00VN39J',
                '00014745474/ 4819   00120784332-M0000000000000000000000000006547',
                'Prelevement',
                '',
                'Banque et assurances',
                '-37,46',
                '',
                '05/12/2022',
                '05/12/2022',
                '0',
            ],
            array_values($lines->current()),
        );
        $this->assertEquals(1, $lines->key());

        $lines->rewind();
        $this->assertEquals(2, $this->start);
        $this->assertEquals(2, $this->advance);
        $this->assertEquals(1, $this->finish);
        $this->assertEquals(0, $lines->key());
        return $lines;
    }

    /**
     * Execute & test writer.
     *
     * @param iterable $entities Processor output.
     *
     * @return void
     * @throws Throwable
     */
    private function write(iterable $entities): void
    {
        /** @var EntityManagerInterface $manager */
        $manager = static::getContainer()->get('doctrine.orm.entity_manager');
        $writer = new OrmWriter($manager);

        $accounts = $manager->createQueryBuilder()
            ->select(['COUNT(a)'])
            ->from(Account::class, 'a')
            ->getQuery()->getSingleScalarResult();
        $transactions = $manager->createQueryBuilder()
            ->select(['COUNT(t)'])
            ->from(Transaction::class, 't')
            ->getQuery()->getSingleScalarResult();

        $wrote = $writer->write($entities);
        $this->assertEquals(8, $entities->key());

        $this->assertCount(15, $wrote);
        $this->assertInstanceOf(Account::class, $wrote[0]);
        $this->assertInstanceOf(ThirdParty::class, $wrote[1]);
        $this->assertInstanceOf(Transaction::class, $wrote[2]);

        $this->assertEquals(
            $accounts + 1,
            $manager->createQueryBuilder()
                ->select(['COUNT(a)'])
                ->from(Account::class, 'a')
                ->getQuery()->getSingleScalarResult()
        );
        $this->assertEquals(
            $transactions + 8,
            $manager->createQueryBuilder()
                ->select(['COUNT(t)'])
                ->from(Transaction::class, 't')
                ->getQuery()->getSingleScalarResult()
        );
    }

}