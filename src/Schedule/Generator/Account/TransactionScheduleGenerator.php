<?php

namespace App\Schedule\Generator\Account;

use App\Entity\Account\Transaction;
use App\Entity\Schedule\TransactionSchedule;
use App\Schedule\Configuration\ScheduleConfigurationInterface;
use App\Schedule\Generator\AbstractScheduleGenerator;

/**
 * Transaction schedule generator.
 */
class TransactionScheduleGenerator extends AbstractScheduleGenerator
{

    /**
     * {@inheritDoc}
     *
     * @param TransactionSchedule $configuration       Transaction schedule
     *                                                 configuration.
     */
    protected function getObject(
        ScheduleConfigurationInterface $configuration
    ): object {
        $transaction = new Transaction();
        $transaction->setName($configuration->getName());
        $transaction->setBalance($configuration->getBalance());
        $transaction->setAccount($configuration->getAccount());
        $transaction->setThirdParty($configuration->getThirdParty());
        $transaction->setCurrency($configuration->getCurrency());
        $transaction->setBudget($configuration->getBudget());

        $date = $configuration->getLastExecution()
            ? $configuration->getLastExecution()->add(
                $configuration->getInterval()
            )
            : $configuration->getStartAt();
        $transaction->setTransactionDate($date);

        return $transaction;
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(
        ScheduleConfigurationInterface $configuration
    ): bool {
        return $configuration::class === TransactionSchedule::class;
    }

}