<?php

namespace App\DataFixtures\Schedule;

use App\DataFixtures\Account\AccountFixture;
use App\DataFixtures\Currency\CurrencyFixture;
use App\Entity\Account\Account;
use App\Entity\Currency\Currency;
use App\Entity\Schedule\TransactionSchedule;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Transaction schedule fixtures loader.
 */
class TransactionScheduleFixture extends Fixture implements
    DependentFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return [
            AccountFixture::class,
            CurrencyFixture::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Account $testAccount */
        $testAccount = $this->getReference(
            AccountFixture::TEST_ACCOUNT_REFERENCE
        );
        /** @var Currency $eur */
        $eur = $this->getReference(
            CurrencyFixture::EUR_REFERENCE,
        );

        $schedule = new TransactionSchedule();
        $schedule->setStartAt(
            DateTime::createFromFormat('Y-m-d H:i:s', '2023-05-25 06:00:00')
        );
        $schedule->setInterval(new DateInterval('P1M'));
        $schedule->setAccount($testAccount);
        $schedule->setName('Salary');
        $schedule->setBalance(2000);
        $schedule->setCurrency($eur);
        $manager->persist($schedule);

        $manager->flush();
    }

}