<?php

namespace App\Lifecycle\Entity\Transaction;

use App\Entity\Transaction\Transaction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Exception\ORMException;
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
            $this->updateBalances($entity, $args);
        }
    }

    /**
     * Handle entity post-persist.
     *
     * @param PostPersistEventArgs $args Event args.
     *
     * @return void
     * @throws ORMException
     */
    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Transaction) {
            $this->flushTransactionLinks($entity, $args->getObjectManager());
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
            $this->updateBalances($entity, $args);
        }
    }

    /**
     * Handle entity post-remove.
     *
     * @param PostRemoveEventArgs $args Event args.
     *
     * @return void
     * @throws ORMException
     */
    public function postRemove(PostRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Transaction) {
            $this->flushTransactionLinks($entity, $args->getObjectManager());
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
            $this->updateBalances($entity, $args);
        }
    }

    /**
     * Handle entity post-update.
     *
     * @param PostUpdateEventArgs $args Event args.
     *
     * @return void
     * @throws ORMException
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Transaction) {
            $this->flushTransactionLinks($entity, $args->getObjectManager());
        }
    }

    /**
     * Flush transaction linked entities that can be changed by pre persist,
     * pre update or pre remove events.
     *
     * This function must be called because cascade are already done on pre
     * persist/update/remove events.
     *
     * @param Transaction   $transaction Updated, created or removed
     *                                   transaction.
     * @param EntityManager $manager     Object manager.
     *
     * @return void
     * @throws ORMException
     */
    private function flushTransactionLinks(
      Transaction $transaction,
      EntityManager $manager
    ): void {
        if ($transaction->getAccount() !== null) {
            $manager->persist($transaction->getAccount());
            $manager->flush($transaction->getAccount());
        }

        if ($transaction->getBudget() !== null) {
            $manager->persist($transaction->getBudget());
            $manager->flush($transaction->getBudget());
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
     * Update transaction account & budget balances according to the transaction
     * lifecycle event.
     *
     * @param Transaction        $transaction Created, updated or removed
     *                                        transaction.
     * @param LifecycleEventArgs $event       Lifecycle event.
     *
     * @return void
     */
    private function updateBalances(
      Transaction $transaction,
      LifecycleEventArgs $event,
    ): void {
        if ($transaction->getAccount() !== null
          || $transaction->getBudget() !== null
        ) {
            $diff = $transaction->getBalance();

            if ($event instanceof PostRemoveEventArgs) {
                $diff = $diff * -1.0;
            }

            if ($event instanceof PreUpdateEventArgs) {
                $changes = $event->getEntityChangeSet();

                if (array_key_exists('balance', $changes)) {
                    $diff = $changes['balance'][1] - $changes['balance'][0];
                }
            }

            $transaction->getAccount()?->addBalance($diff);
            $transaction->getBudget()?->addBalance($diff);

            $event->getObjectManager()->persist($transaction->getAccount());
            $event->getObjectManager()->persist($transaction->getBudget());
        }
    }


}