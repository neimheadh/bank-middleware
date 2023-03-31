<?php

namespace App\Tests\Import\Processor\Parser;

use App\Import\Exception\Processor\ParserInputNotSupportedException;
use App\Import\Exception\Processor\ParseValueException;
use App\Import\Processor\Parser\FloatParser;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * FloatParser test suite.
 */
class FloatParserTest extends TestCase
{

    /**
     * Test float parser.
     *
     * @return void
     */
    public function testFloatParser(): void
    {
        $this->assertSame(10.0, (new FloatParser())->parse('10'));
        $this->assertSame(25.7, (new FloatParser())->parse('25.7'));
        $this->assertSame(1.3, (new FloatParser())->parse('1,3'));
        $this->assertSame(1025.212, (new FloatParser())->parse('1 025.212'));
        $this->assertSame(-12.25, (new FloatParser())->parse('-12.25'));
        $this->assertSame(15.961, (new FloatParser())->parse('+15.961'));
        $this->assertSame(15.98, (new FloatParser(round: 2))->parse(15.983));
        $this->assertSame(15.98, (new FloatParser(floor: 2))->parse(15.987));
        $this->assertSame(15.99, (new FloatParser(ceil: 2))->parse(15.983));
        $this->assertSame(
            1589.589,
            (new FloatParser(
                thousandSeparators: '@', decimalSeparators: '_'
            ))->parse('1@589_589')
        );

        $e = null;
        try {
            (new FloatParser())->parse('Gna!');
        } catch (ParseValueException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Unable to transform "Gna!" into float value.',
            $e->getMessage()
        );
        $this->assertSame(0.0, (new FloatParser(noError: true))->parse('Gna!'));

        $e = null;
        try {
            (new FloatParser())->parse(new stdClass());
        } catch (ParserInputNotSupportedException $e) {
        }
        $this->assertNotNull($e);
        $this->assertEquals(
            'Given input of type "object" not supported by "App\Import\Processor\Parser\FloatParser".',
            $e->getMessage()
        );

        $e = null;
        try {
            $this->assertSame(
                0.0,
                (new FloatParser(noError: true))->parse(new stdClass())
            );
        } catch (ParserInputNotSupportedException $e) {
        }
        $this->assertNull($e);
    }

}