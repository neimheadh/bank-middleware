<?php

namespace App\Tests\Entity\Currency;

use App\Entity\Currency\Currency;
use PHPUnit\Framework\TestCase;

/**
 * Currency test suite.
 */
class CurrencyTest extends TestCase
{

    /**
     * Test the currency conversion work well.
     *
     * @test
     * @functionnal
     *
     * @return void
     */
    public function shouldConvertCorrectly(): void
    {
        $usd = new Currency();
        $usd->setCode('USD');
        $usd->setUsdExchangeRate(1);

        $eur = new Currency();
        $eur->setCode('EUR');
        $eur->setUsdExchangeRate(1.0674);

        $rub = new Currency();
        $rub->setCode('RUB');
        $rub->setUsdExchangeRate(0.0132);

        $this->assertEquals(1.0674, $usd->convert($eur, 1));
        $this->assertEquals(0.0132, $usd->convert($rub, 1));
        $this->assertEquals(0.0124, round($eur->convert($rub, 1), 4));
        $this->assertEquals(80.8636, round($rub->convert($eur, 1), 4));
    }
}