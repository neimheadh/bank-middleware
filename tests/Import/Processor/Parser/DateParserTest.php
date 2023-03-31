<?php

namespace App\Tests\Import\Processor\Parser;

use App\Import\Exception\Processor\ParserInputNotSupportedException;
use App\Import\Processor\Parser\DateParser;
use DateTime;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;

/**
 * DateParser test case.
 */
class DateParserTest extends TestCase
{

    /**
     * Test DateParser.
     *
     * @test
     * @functionnal
     *
     * @return void
     * @throws Throwable
     */
    public function testDateParser(): void
    {
        $this->assertEquals(
            '2022-01-01',
            (new DateParser())
                ->parse(
                    DateTime::createFromFormat(
                        'Y-m-d',
                        '2022-01-01'
                    )
                )->format('Y-m-d')
        );
        $this->assertEquals(
            '1987-12-11',
            (new DateParser(format: 'd/m/Y'))->parse('11/12/1987')
                ->format('Y-m-d')
        );

        $e = null;
        try {
            (new DateParser())->parse(new stdClass());
        } catch (ParserInputNotSupportedException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            sprintf(
                'Given input of type "object" not supported by "%s".',
                DateParser::class
            ),
            $e->getMessage()
        );
    }

}