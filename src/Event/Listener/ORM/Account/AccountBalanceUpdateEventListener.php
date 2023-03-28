<?php

namespace App\Event\Listener\ORM\Account;

use App\Entity\Account\Transaction;
use App\Model\Event\Listener\ORM\DoctrineEntityListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePrePersistEntityListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreRemoveEntityListenerInterface;
use App\Model\Event\Listener\ORM\DoctrinePreUpdateEntityListenerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Update account balance according to the transactions movements.
 *
 * @implements DoctrinePrePersistEntityListenerInterface<Transaction>
 * @implements DoctrinePreRemoveEntityListenerInterface<Transaction>
 * @implements DoctrinePreUpdateEntityListenerInterface<Transaction>
 */
class AccountBalanceUpdateEventListener implements
    DoctrineEntityListenerInterface,
    DoctrinePrePersistEntityListenerInterface,
    DoctrinePreRemoveEntityListenerInterface,
    DoctrinePreUpdateEntityListenerInterface
{

    /**
     * {@inheritDoc}
     */
    public static function getEntityClass(): string
    {
        return Transaction::class;
    }

    /**
     * {@inheritDoc}
     *
     * Add created transaction balance to its account balance.
     *
     * @param Transaction $entity Persisted transaction.
     */
    public function prePersist(
        object $entity,
        PrePersistEventArgs $args
    ): void {
        $entity->getAccount()?->addBalance(
            $entity->getBalance(),
            $entity->getCurrency()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param Transaction $entity Removed transaction.
     */
    public function preRemove(
        object $entity,
        PreRemoveEventArgs $args
    ): void {
        $entity->getAccount()?->subtractBalance(
            $entity->getBalance(),
            $entity->getCurrency()
        );
    }

    /**
     * {@inheritDoc}
     *
     * Change update transaction account balance according to the new balance
     * value.
     *
     * @param Transaction $entity Updated transaction.
     */
    public function preUpdate(
        object $entity,
        PreUpdateEventArgs $args
    ): void {
        $args->getOldValue('account')->subtractBalance(
            $args->getOldValue('balance'),
            $args->getOldValue('currency'),
        );
        $entity->getAccount()?->addBalance(
            $entity->getBalance(),
            $entity->getCurrency()
        );
    }

}