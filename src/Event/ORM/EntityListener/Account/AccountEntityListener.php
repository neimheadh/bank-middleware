<?php

namespace App\Event\ORM\EntityListener\Account;

use App\Entity\Account\Account;
use App\Repository\Account\TransactionRepository;

/**
 * Account lifecycle event listener.
 */
class AccountEntityListener
{

    /**
     * @param TransactionRepository $transactionRepository Transaction
     *                                                     repository.
     */
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    /**
     * Handle doctrine postLoad event.
     *
     * @param Account $account Loaded account.
     *
     * @return void
     * @internal
     */
    public function postLoad(Account $account): void
    {
        $this->calculateFutureBalance($account);
    }

    /**
     * Handle doctrine postPersist event.
     *
     * @param Account $account Persisted account.
     *
     * @return void
     */
    public function postPersist(Account $account): void
    {
        $this->calculateFutureBalance($account);
    }

    /**
     * Handle doctrine postUpdate event.
     *
     * @param Account $account Updated account.
     *
     * @return void
     */
    public function postUpdate(Account $account): void
    {
        $this->calculateFutureBalance($account);
    }

    /**
     * Calculate account balance when remaining transaction will be processed.
     *
     * @param Account $account Account having balance calculated.
     *
     * @return void
     */
    private function calculateFutureBalance(
        Account $account
    ): void {
        $remainingTransactions = $this->transactionRepository
            ->findRemainingTransactionsForAccount($account);
        $balance = $account->getBalance();

        foreach ($remainingTransactions as $transaction) {
            $balance += $transaction->getBalance($account->getCurrency());
        }

        $account->setFutureBalance($balance);
    }

}