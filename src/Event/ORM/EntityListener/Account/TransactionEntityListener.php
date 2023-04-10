<?php

namespace App\Event\ORM\EntityListener\Account;


use App\Entity\Account\Transaction;
use App\Model\Entity\Currency\BalancedEntityInterface;
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
        $transaction->getBudget()?->addBalance(
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
        $transaction->getBudget()?->subtractBalance(
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
        $transaction->getAccount() && $this->updateBalanced(
            $transaction,
            $args,
            $transaction->getAccount(),
            'account',
            true
        );
        $transaction->getBudget() && $this->updateBalanced(
            $transaction,
            $args,
            $transaction->getBudget(),
            'budget',
            false
        );
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
    private function updateBalanced(
        Transaction $transaction,
        PreUpdateEventArgs $args,
        BalancedEntityInterface $balanced,
        string $field,
        bool $processDate
    ): void {
        if ($processDate && $args->hasChangedField('processDate')) {
            $oldProcessDate = $args->getOldValue('processDate');
            $processDate = $transaction->getProcessDate();


            if ($oldProcessDate === null && $processDate !== null) {
                $balanced->addBalance(
                    $transaction->getBalance(),
                    $transaction->getCurrency()
                );

                return;
            }

            if ($oldProcessDate !== null && $processDate === null) {
                $balanced->subtractBalance(
                    $transaction->getBalance(),
                    $transaction->getCurrency()
                );

                return;
            }
        }

        $oldValue = $args->hasChangedField($field)
            ? $args->getOldValue($field)
            : $transaction->getAccount();
        $oldBalance = $args->hasChangedField('balance')
            ? $args->getOldValue('balance')
            : $transaction->getBalance();
        $oldCurrency = $args->hasChangedField('currency')
            ? $args->getOldValue('currency')
            : $transaction->getCurrency();

        $oldValue?->subtractBalance($oldBalance, $oldCurrency);
        $balanced->addBalance(
            $transaction->getBalance(),
            $transaction->getCurrency()
        );
    }

}