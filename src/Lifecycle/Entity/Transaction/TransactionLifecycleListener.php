<?php

namespace App\Lifecycle\Entity\Transaction;

use App\Entity\Transaction\Transaction;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Transaction lifecycle listener.
 */
class TransactionLifecycleListener
{

    /**
     * Handle entity pre-persist.
     *
     * @param PrePersistEventArgs $args Event args.
     *
     * @return void
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Transaction) {
            $this->setTransactionCurrency($entity);
            $this->setTransactionOwner($entity);
            $this->updateAccountBalance($entity, $args);
        }
    }

    /**
     * Handle entity pre-remove.
     *
     * @param PreRemoveEventArgs $args Event args.
     *
     * @return void
     */
    public function preRemove(PreRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Transaction) {
            $this->updateAccountBalance($entity, $args);
        }
    }

    /**
     * Handle entity pre-update.
     *
     * @param PreUpdateEventArgs $args Event args.
     *
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Transaction) {
            $this->updateAccountBalance($entity, $args);
        }
    }

    /**
     * Set the transaction currency to its account default currency if not
     * set.
     *
     * @param Transaction $transaction Created transaction.
     *
     * @return void
     */
    private function setTransactionCurrency(Transaction $transaction): void
    {
        if ($transaction->getCurrency() === null
          && $transaction->getAccount()?->getCurrency() !== null
        ) {
            $transaction->setCurrency(
              $transaction->getAccount()->getCurrency()
            );
        }
    }

    /**
     * Set the transaction owner according to its account.
     *
     * @param Transaction $transaction Created transaction.
     *
     * @return void
     */
    private function setTransactionOwner(Transaction $transaction): void
    {
        if ($transaction->getOwner() === null
          && $transaction->getAccount()?->getOwner() !== null
        ) {
            $transaction->setOwner(
              $transaction->getAccount()->getOwner()
            );
        }
    }

    /**
     * Update transaction account balance according to the transaction lifecycle
     * event.
     *
     * @param Transaction        $transaction Created, updated or removed
     *                                        transaction.
     * @param LifecycleEventArgs $event       Lifecycle event.
     *
     * @return void
     */
    private function updateAccountBalance(
      Transaction $transaction,
      LifecycleEventArgs $event,
    ): void {
        if ($transaction->getAccount() !== null) {
            $account = $transaction->getAccount();

            if ($event instanceof PrePersistEventArgs) {
                $account->addBalance($transaction->getBalance());
                return;
            }

            if ($event instanceof PreRemoveEventArgs) {
                $account->reduceBalance($transaction->getBalance());
                return;
            }

            if ($event instanceof PreUpdateEventArgs) {
                $changes = $event->getEntityChangeSet();

                if (array_key_exists('balance', $changes)) {
                    $diff = $changes['balance'][1] - $changes['balance'][0];
                    $account->addBalance($diff);
                }
            }
        }
    }

}