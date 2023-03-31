<?php

namespace App\Tests\Import\Reader\Result;

use App\Import\Reader\Result\CsvFileIterator;
use Monolog\Test\TestCase;
use SplFileObject;

/**
 * CsvFileIterator test suite.
 */
class CsvFileIteratorTest extends TestCase
{

    /**
     * Test CsvFileIterator with an headed csv.
     *
     * @test
     * @functionnal
     *
     * @return void
     */
    public function testHeadedCsv(): void
    {
        $csv = new CsvFileIterator(
            __DIR__ . '/../../../Resources/import/bp.csv',
            ';'
        );

        $this->assertCount(8, $csv);
        $data = iterator_to_array($csv);
        $this->assertCount(8, $data);

        $first = current($data);
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
     * Test CsvFileIterator with a CSV without header.
     *
     * @test
     * @functionnal
     *
     * @return void
     */
    public function testUnHeadedCsv(): void
    {
        $csv = new CsvFileIterator(
            new SplFileObject(__DIR__ . '/../../../Resources/import/bp.csv'),
            ';',
            headed: false
        );

        $this->assertCount(9, $csv);
        $data = iterator_to_array($csv);
        $this->assertCount(9, $data);

        $first = current($data);
        $this->assertCount(13, $first);
        $this->assertEquals([
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
        ], $first);
    }

    /**
     * Test CsvFileIterator with an empty file.
     *
     * @test
     * @functionnal
     *
     * @return void
     */
    public function testEmptyFile(): void
    {
        $csv = new CsvFileIterator(
            new SplFileObject(__DIR__ . '/../../../Resources/import/empty.csv'),
        );

        $this->assertNull($csv->current());
        $this->assertCount(0, $csv);
        $this->assertCount(0, iterator_to_array($csv));

        $csv = new CsvFileIterator(
            new SplFileObject(__DIR__ . '/../../../Resources/import/empty.csv'),
            headed: false
        );

        $this->assertNull($csv->current());
        $this->assertCount(0, $csv);
        $this->assertCount(0, iterator_to_array($csv));
    }

    /**
     * Test CsvFileIterator with a discontinued line CSV file.
     *
     * @test
     * @functionnal
     *
     * @return void
     */
    public function testDiscontinuedFile(): void
    {
        $csv = new CsvFileIterator(
            __DIR__ . '/../../../Resources/import/discontinued.csv'
        );

        $this->assertCount(7, $csv);
        $data = iterator_to_array($csv);
        $this->assertCount(7, $data);

        $this->assertEquals([
            'Id' => 1,
            'Name' => 'Jean'
        ], $data[0]);
        $this->assertEquals([
            'Id' => 2,
            'Name' => null,
        ], $data[1]);
        $this->assertNull($data[2]);
        $this->assertNull($data[3]);
        $this->assertEquals(
            [
                'Id' => 3,
                'Name' => 'Philip',
                2 => 'Sanchez'
            ],
            $data[4]
        );
        $this->assertNull($data[5]);
        $this->assertNull($data[6]);
    }
}