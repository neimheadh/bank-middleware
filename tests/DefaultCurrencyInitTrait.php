<?php

namespace App\Tests;

use App\Entity\Currency\Currency;
use App\Repository\Currency\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Default currency test trait.
 */
trait DefaultCurrencyInitTrait
{
    /**
     * Doctrine ORM manager.
     *
     * @var EntityManagerInterface|null
     */
    private ?EntityManagerInterface $manager = null;

    /**
     * Euro currency.
     *
     * @var Currency|null
     */
    private ?Currency $eur = null;

    /**
     * Usd currency.
     *
     * @var Currency|null
     */
    private ?Currency $usd = null;

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

        $this->eur = $currencyRepository->findOneByCode('EUR') ?: new Currency();
        $this->eur->setDefault(true);
        $this->eur->setCode('EUR');
        $this->eur->setRounded(2);
        $this->eur->setUsdExchangeRate(1.3);
        $this->manager->persist($this->eur);
        $this->manager->flush();

        $this->usd = $currencyRepository->findOneByCode('USD') ?: new Currency();
        $this->usd->setCode('USD');
        $this->usd->setRounded(2);
        $this->usd->setUsdExchangeRate(1);
        $this->manager->persist($this->usd);
        $this->manager->flush();
    }
}