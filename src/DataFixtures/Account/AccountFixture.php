<?php

namespace App\DataFixtures\Account;

use App\DataFixtures\Currency\CurrencyFixture;
use App\Entity\Account\Account;
use App\Entity\Currency\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Account fixtures loader.
 */
class AccountFixture extends Fixture implements DependentFixtureInterface
{

    /**
     * Test account reference key.
     */
    public const TEST_ACCOUNT_REFERENCE = 'account--test';

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            CurrencyFixture::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Currency $eur */
        $eur = $this->getReference(
            CurrencyFixture::EUR_REFERENCE,
        );

        $account = new Account();
        $account->setName('Test');
        $account->setCode('TST');
        $account->setBalance(0);
        $account->setCurrency($eur);
        $manager->persist($account);
        $this->addReference(self::TEST_ACCOUNT_REFERENCE, $account);

        $manager->flush();

    }

}