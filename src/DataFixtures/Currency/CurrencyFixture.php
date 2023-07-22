<?php

namespace App\DataFixtures\Currency;

use App\Entity\Currency\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Currency fixtures loader.
 */
class CurrencyFixture extends Fixture
{

    /**
     * Euro currency reference key.
     */
    public const EUR_REFERENCE = 'currency--eur';

    /**
     * Dollar currency reference key.
     */
    public const USD_REFERENCE = 'currency--usd';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $usd = new Currency();
        $usd->setSymbol('$');
        $usd->setName('Dollar');
        $usd->setNativeSymbol('$');
        $usd->setDecimalDigits(2);
        $usd->setRounded(2);
        $usd->setCode('USD');
        $usd->setPluralName('Dollars');
        $usd->setUsdExchangeRate(1);
        $this->addReference(self::USD_REFERENCE, $usd);
        $manager->persist($usd);

        $eur = new Currency();
        $eur->setSymbol('€');
        $eur->setName('Euro');
        $eur->setNativeSymbol('€');
        $eur->setDecimalDigits(2);
        $eur->setRounded(2);
        $eur->setCode('EUR');
        $eur->setPluralName('Euros');
        $eur->setUsdExchangeRate(1.09);
        $eur->setDefault(true);
        $this->addReference(self::EUR_REFERENCE, $eur);
        $manager->persist($eur);

        $manager->flush();
    }

}