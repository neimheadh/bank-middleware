<?php

namespace App\Event\ORM\EntityListener\Account;


use App\Entity\Account\Transaction;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Transaction lifecycle event listener.
 */
final class TransactionEntityListener
{

    /**
     * Handle transaction pre persist event.
     *
     * @param Transaction         $transaction Persisted transaction.
     * @param PrePersistEventArgs $args        Event arguments.
     *
     * @return void
     * @internal
     */
    public function prePersist(
        Transaction $transaction,
        PrePersistEventArgs $args
    ): void {
        $transaction->isProcessed() && $transaction->getAccount()?->addBalance(
            $transaction->getBalance(),
            $transaction->getCurrency()
        );
    }

    /**
     * Handle transaction pre remove event.
     *
     * @param Transaction        $transaction Removed transaction.
     * @param PreRemoveEventArgs $args        Event arguments.
     *
     * @return void
     * @internal
     */
    public function preRemove(
        Transaction $transaction,
        PreRemoveEventArgs $args
    ): void {
        $transaction->isProcessed() && $transaction->getAccount()
            ?->subtractBalance(
                $transaction->getBalance(),
                $transaction->getCurrency()
            );
    }

    /**
     * Handle transaction pre update event.
     *
     * @param Transaction        $transaction Updated transaction.
     * @param PreUpdateEventArgs $args        Event arguments.
     *
     * @return void
     * @internal
     */
    public function preUpdate(
        Transaction $transaction,
        PreUpdateEventArgs $args
    ): void {
        $manager = $args->getObjectManager();
        $this->updateAccountBalance($transaction, $args);
        $manager->persist($transaction->getAccount());
    }

    /**
     * Update transaction account balance.
     *
     * @param Transaction        $transaction Transaction.
     * @param PreUpdateEventArgs $args        Event arguments.
     *
     * @return void
     */
    private function updateAccountBalance(
        Transaction $transaction,
        PreUpdateEventArgs $args
    ): void {
        if ($args->hasChangedField('processDate')) {
            $oldProcessDate = $args->getOldValue('processDate');
            $processDate = $transaction->getProcessDate();


            if ($oldProcessDate === null && $processDate !== null) {
                $transaction->getAccount()?->addBalance(
                    $transaction->getBalance(),
                    $transaction->getCurrency()
                );

                return;
            }

            if ($oldProcessDate !== null && $processDate === null) {
                $transaction->getAccount()?->subtractBalance(
                    $transaction->getBalance(),
                    $transaction->getCurrency()
                );

                return;
            }
        }

        $oldAccount = $args->hasChangedField('account')
            ? $args->getOldValue('account')
            : $transaction->getAccount();
        $oldBalance = $args->hasChangedField('balance')
            ? $args->getOldValue('balance')
            : $transaction->getBalance();
        $oldCurrency = $args->hasChangedField('currency')
            ? $args->getOldValue('currency')
            : $transaction->getCurrency();

        $oldAccount?->subtractBalance($oldBalance, $oldCurrency);
        $transaction->getAccount()?->addBalance(
            $transaction->getBalance(),
            $transaction->getCurrency()
        );
    }

}