<?php

namespace App\Tests\Import\Reader;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Exception\Reader\CannotReadFileException;
use App\Import\Reader\CsvFileReader;
use Monolog\Test\TestCase;
use RuntimeException;
use SplFileObject;
use stdClass;
use Throwable;

/**
 * CsvFileReader test suite.
 */
class CsvFileReaderTest extends TestCase
{

    /**
     * Test a file path import.
     *
     * @test
     * @functionnal
     *
     * @return void
     * @throws Throwable
     */
    public function testCsvFilePathImport(): void
    {
        $reader = new CsvFileReader();
        $result = $reader->read(
            __DIR__ . '/../../Resources/import/bp.csv',
            [CsvFileReader::OPTION_SEPARATOR => ';']
        );

        $this->assertCount(8, $result);

        $first = $result->current();
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
        $this->assertSame(
            'SNCF INTERNET PARIS 10',
            $first['Libelle operation']
        );
        $this->assertSame('1RI8LIG', $first['Reference']);
        $this->assertSame(
            '060223 CB*********-',
            $first['Informations complementaires']
        );
        $this->assertSame('Carte bancaire', $first['Type operation']);
        $this->assertSame('Transports', $first['Categorie']);
        $this->assertSame('Trains, avions et ferrys', $first['Sous categorie']);
        $this->assertSame('', $first['Debit']);
        $this->assertSame('+62,00', $first['Credit']);
        $this->assertSame('07/02/2023', $first['Date operation']);
        $this->assertSame('07/02/2023', $first['Date de valeur']);
        $this->assertSame('0', $first['Pointage operation']);

        $result = $reader->read(
            new SplFileObject(__DIR__ . '/../../Resources/import/bp.csv'),
            [CsvFileReader::OPTION_SEPARATOR => ';']
        );

        $this->assertCount(8, $result);

        $first = $result->current();
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
        $this->assertSame(
            'SNCF INTERNET PARIS 10',
            $first['Libelle operation']
        );
        $this->assertSame('1RI8LIG', $first['Reference']);
        $this->assertSame(
            '060223 CB*********-',
            $first['Informations complementaires']
        );
        $this->assertSame('Carte bancaire', $first['Type operation']);
        $this->assertSame('Transports', $first['Categorie']);
        $this->assertSame('Trains, avions et ferrys', $first['Sous categorie']);
        $this->assertSame('', $first['Debit']);
        $this->assertSame('+62,00', $first['Credit']);
        $this->assertSame('07/02/2023', $first['Date operation']);
        $this->assertSame('07/02/2023', $first['Date de valeur']);
        $this->assertSame('0', $first['Pointage operation']);
    }

    /**
     * Test use reader exceptions.
     *
     * @test
     * @functionnal
     *
     * @return void
     * @throws Throwable
     */
    public function testExceptions(): void
    {
        $reader = new CsvFileReader();

        $e = null;
        try {
            $reader->read(__DIR__ . '/not_existing.csv');
        } catch (RuntimeException $e) {
        }

        $this->assertNotNull($e);
        $this->assertEquals(
            sprintf(
                'SplFileObject::__construct(%s): Failed to open stream: No such file or directory',
                __DIR__ . '/not_existing.csv',
            ),
            $e->getMessage()
        );

        $e = null;
        try {
            $reader->read(new stdClass());
        } catch (InputNotSupportedException $e) {}
        $this->assertNotNull($e);
        $this->assertEquals(
            sprintf(
                'Given input of type "object" not supported by "%s".',
                CsvFileReader::class
            ),
            $e->getMessage()
        );
    }
}