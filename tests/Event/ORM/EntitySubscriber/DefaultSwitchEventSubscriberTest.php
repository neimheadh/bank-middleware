<?php

namespace App\Tests\Event\ORM\EntitySubscriber;

use App\Entity\Currency\Currency;
use App\Repository\Currency\CurrencyRepository;
use App\Tests\DefaultCurrencyInitTrait;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * DefaultSwitchEventSubscriber test suite.
 */
class DefaultSwitchEventSubscriberTest extends KernelTestCase
{
    use DefaultCurrencyInitTrait;

    /**
     * Test default currency automatically set when none.
     *
     * @test
     *
     * @return void
     */
    public function testCurrencyDefaultAutomaticallySet(): void
    {
        $manager = static::getContainer()->get('doctrine.orm.entity_manager');
        /** @var CurrencyRepository $currencyRepository */
        $currencyRepository = $manager->getRepository(Currency::class);

        $manager->createQueryBuilder()
            ->delete(Currency::class, 'c')
            ->where('c.code = :code')
            ->getQuery()->execute(['code' => 'TST']);

        $manager->clear();
        $eur = $currencyRepository->findOneByCode('EUR');
        $this->assertNotNull($eur);
        $this->assertTrue($eur->isDefault());
        $manager->persist($eur);
        $manager->flush();

        $currency = new Currency();
        $currency->setCode('TST');
        $currency->setName('Test');
        $currency->setDefault(true);
        $manager->persist($currency);
        $manager->flush();
        $this->assertTrue($currency->isDefault());

        $manager->clear();
        $eur = $currencyRepository->findOneByCode('EUR');
        $this->assertNotNull($eur);
        $this->assertFalse($eur->isDefault());
        $eur->setDefault(true);
        $manager->persist($eur);
        $manager->flush();

        $manager->clear();
        $currency = $currencyRepository->findOneByCode('TST');
        $this->assertNotNull($currency);
        $this->assertFalse($currency->isDefault());
        $eur = $currencyRepository->findOneByCode('EUR');
        $this->assertNotNull($eur);
        $this->assertTrue($eur->isDefault());
    }
}