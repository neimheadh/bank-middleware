<?php

namespace App\Tests\Import;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Entity\Currency\Currency;
use App\Import\Configuration\ConfigurationReader;
use App\Import\Configuration\File\CsvFileImportConfiguration;
use App\Import\Parser\DateParser;
use App\Import\Parser\EntityReferenceParser;
use App\Import\Parser\FixedValueParser;
use App\Import\Parser\FloatParser;
use App\Import\Parser\SumValueParser;
use App\Import\Processor\ArrayToOrmProcessor;
use App\Import\Processor\ProcessorInterface;
use App\Import\Reader\File\CsvFileReader;
use App\Import\Reader\ReaderInterface;
use App\Import\Writer\OrmWriter;
use App\Import\Writer\WriterInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;

/**
 * Full import test suite.
 */
class FullImportTest extends KernelTestCase
{

    /**
     * Test Banque Populaire transaction csv import.
     *
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function testBpTransactionImport(): void
    {
        $input = __DIR__ . '/../Resources/import/bp.csv';
        $configFile = __DIR__ . '/../Resources/import/bp.conf.yaml';

        /** @var TestContainer $container */
        $container = static::getContainer();
        /** @var ConfigurationReader $confReader */
        $confReader = $container->get(ConfigurationReader::class);

        /** @var CsvFileImportConfiguration $configuration */
        $configuration = $confReader->readYamlFile($configFile);
        $this->assertInstanceOf(CsvFileImportConfiguration::class, $configuration);
        $this->assertSame(';', $configuration->separator);
        $this->assertSame('"', $configuration->enclosure);
        $this->assertSame('\\', $configuration->escape);
        $this->assertSame(true, $configuration->headed);
        $this->assertSame(true, $configuration->trimValues);
        $this->assertCount(2, $configuration->dataMap);
        $this->assertArrayHasKey('account', $configuration->dataMap);
        $this->assertArrayHasKey('transaction', $configuration->dataMap);

        $this->assertEquals(['class', 'identifier', 'map'], array_keys($configuration->dataMap['account']));
        $this->assertEquals(Account::class, $configuration->dataMap['account']['class']);
        $this->assertEquals(1, $configuration->dataMap['account']['identifier']);
        $this->assertEquals(['currency', 'code', 'name'], array_keys($configuration->dataMap['account']['map']));
        $this->assertEquals(['parser', 'value'], array_keys($configuration->dataMap['account']['map']['currency']));
        $this->assertEquals('EUR', $configuration->dataMap['account']['map']['currency']['value']);
        $this->assertInstanceOf(EntityReferenceParser::class, $configuration->dataMap['account']['map']['currency']['parser']);
        $this->assertEquals(Currency::class, $configuration->dataMap['account']['map']['currency']['parser']->class);
        $this->assertEquals(['code'], $configuration->dataMap['account']['map']['currency']['parser']->fields);
        $this->assertEquals(['parser'], array_keys($configuration->dataMap['account']['map']['code']));
        $this->assertInstanceOf(FixedValueParser::class, $configuration->dataMap['account']['map']['code']['parser']);
        $this->assertEquals('000001', $configuration->dataMap['account']['map']['code']['parser']->value);
        $this->assertEquals(['parser'], array_keys($configuration->dataMap['account']['map']['name']));
        $this->assertInstanceOf(FixedValueParser::class, $configuration->dataMap['account']['map']['name']['parser']);
        $this->assertEquals('Test account', $configuration->dataMap['account']['map']['name']['parser']->value);

        $this->assertEquals(['class', 'map'], array_keys($configuration->dataMap['transaction']));
        $this->assertEquals(Transaction::class, $configuration->dataMap['transaction']['class']);
        $this->assertEquals(['account', 'currency', 'createdAt', 'name', 'balance'], array_keys($configuration->dataMap['transaction']['map']));
        $this->assertEquals(['reference'], array_keys($configuration->dataMap['transaction']['map']['account']));
        $this->assertEquals('account@1', $configuration->dataMap['transaction']['map']['account']['reference']);
        $this->assertEquals(['parser', 'value'], array_keys($configuration->dataMap['transaction']['map']['currency']));
        $this->assertEquals('EUR', $configuration->dataMap['transaction']['map']['currency']['value']);
        $this->assertInstanceOf(EntityReferenceParser::class, $configuration->dataMap['transaction']['map']['currency']['parser']);
        $this->assertEquals(Currency::class, $configuration->dataMap['transaction']['map']['currency']['parser']->class);
        $this->assertEquals(['code'], $configuration->dataMap['transaction']['map']['currency']['parser']->fields);
        $this->assertEquals(['parser', 'field'], array_keys($configuration->dataMap['transaction']['map']['createdAt']));
        $this->assertEquals('Date operation', $configuration->dataMap['transaction']['map']['createdAt']['field']);
        $this->assertInstanceOf(DateParser::class, $configuration->dataMap['transaction']['map']['createdAt']['parser']);
        $this->assertEquals('d/m/Y', $configuration->dataMap['transaction']['map']['createdAt']['parser']->format);
        $this->assertEquals(['field'], array_keys($configuration->dataMap['transaction']['map']['name']));
        $this->assertEquals('Libelle operation', $configuration->dataMap['transaction']['map']['name']['field']);
        $this->assertEquals(['parser', 'field'], array_keys($configuration->dataMap['transaction']['map']['balance']));
        $this->assertEquals(['Debit', 'Credit'], $configuration->dataMap['transaction']['map']['balance']['field']);
        $this->assertInstanceOf(SumValueParser::class, $configuration->dataMap['transaction']['map']['balance']['parser']);
        $this->assertCount(2, $configuration->dataMap['transaction']['map']['balance']['parser']->parsers);
        $this->assertInstanceOf(FloatParser::class, $configuration->dataMap['transaction']['map']['balance']['parser']->parsers[0]);
        $this->assertInstanceOf(FloatParser::class, $configuration->dataMap['transaction']['map']['balance']['parser']->parsers[1]);
        $this->assertEquals(2, $configuration->dataMap['transaction']['map']['balance']['parser']->parsers[0]->round);
        $this->assertEquals(2, $configuration->dataMap['transaction']['map']['balance']['parser']->parsers[1]->round);
        $this->assertEquals(FloatParser::ROUND_CEIL, $configuration->dataMap['transaction']['map']['balance']['parser']->parsers[0]->roundMethod);
        $this->assertEquals(FloatParser::ROUND_FLOOR, $configuration->dataMap['transaction']['map']['balance']['parser']->parsers[1]->roundMethod);

        /** @var ReaderInterface $reader */
        $reader = $container->get(CsvFileReader::class);
        $result = $reader->read($input, $configuration);
        $data = iterator_to_array($result);
        $first = current($data);
        $this->assertCount(8, $data);
        $this->assertCount(13, $first);
        $this->assertArrayHasKey('Date de comptabilisation', $first);
        $this->assertArrayHasKey('Libelle simplifie', $first);
        $this->assertArrayHasKey('Libelle operation', $first);
        $this->assertArrayHasKey('Reference', $first);
        $this->assertArrayHasKey('Informations complementaires', $first);
        $this->assertArrayHasKey('Type operation', $first);
        $this->assertArrayHasKey('Categorie', $first);
        $this->assertArrayHasKey('Sous categorie', $first);
        $this->assertArrayHasKey('Debit', $first);
        $this->assertArrayHasKey('Credit', $first);
        $this->assertArrayHasKey('Date operation', $first);
        $this->assertArrayHasKey('Date de valeur', $first);
        $this->assertArrayHasKey('Pointage operation', $first);
        $this->assertSame('07/02/2023', $first['Date de comptabilisation']);
        $this->assertSame('SNCF', $first['Libelle simplifie']);
        $this->assertSame('SNCF INTERNET PARIS 10', $first['Libelle operation']);
        $this->assertSame('1RI8LIG', $first['Reference']);
        $this->assertSame('060223 CB*********-', $first['Informations complementaires']);
        $this->assertSame('Carte bancaire', $first['Type operation']);
        $this->assertSame('Transports', $first['Categorie']);
        $this->assertSame('Trains, avions et ferrys', $first['Sous categorie']);
        $this->assertNull($first['Debit']);
        $this->assertSame('+62,00', $first['Credit']);
        $this->assertSame('07/02/2023', $first['Date operation']);
        $this->assertSame('07/02/2023', $first['Date de valeur']);
        $this->assertSame('0', $first['Pointage operation']);

        /** @var ProcessorInterface $processor */
        $processor = $container->get(ArrayToOrmProcessor::class);
        $result = $processor->process($result, $configuration);
        $data = iterator_to_array($result);
        $this->assertCount(8, $data);
        /** @var Account $account */
        $account = $data[0][0];
        /** @var Transaction $transaction */
        $transaction = $data[0][1];
        $this->assertInstanceOf(Account::class, $account);
        $this->assertNull($account->getId());
        $this->assertSame('000001', $account->getCode());
        $this->assertSame('Test account', $account->getName());
        $this->assertNotNull($account->getCurrency());
        $this->assertEquals('EUR', $account->getCurrency()->getCode());
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertNull($transaction->getId());
        $this->assertSame($account, $transaction->getAccount());
        $this->assertNotNull($transaction->getCurrency());
        $this->assertEquals('EUR', $transaction->getCurrency()->getCode());
        $this->assertSame('07/02/2023', $transaction->getCreatedAt()->format('d/m/Y'));
        $this->assertSame('SNCF INTERNET PARIS 10', $transaction->getName());
        $this->assertSame(62.0, $transaction->getBalance());
        /** @var Transaction $transaction */
        $transaction = $data[7][1];
        $this->assertInstanceOf(Account::class, $data[7][0]);
        $this->assertSame($account, $data[7][0]);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertNull($transaction->getId());
        $this->assertSame($account, $transaction->getAccount());
        $this->assertEquals('EUR', $transaction->getCurrency()->getCode());
        $this->assertNotNull($transaction->getCurrency());
        $this->assertSame('08/09/2022', $transaction->getCreatedAt()->format('d/m/Y'));
        $this->assertSame('SNCF INTERNET PARIS 10', $transaction->getName());
        $this->assertSame(48.0, $transaction->getBalance());

        /** @var WriterInterface $writer */
        $writer = $container->get(OrmWriter::class);
        $result = $writer->write($result, $configuration);
        $data = iterator_to_array($result);
        /** @var Account $account */
        $account = $data[0][0];
        /** @var Transaction $transaction */
        $transaction = $data[0][1];
        $this->assertNotNull($account->getId());
        $this->assertNotNull($transaction->getId());
    }
}