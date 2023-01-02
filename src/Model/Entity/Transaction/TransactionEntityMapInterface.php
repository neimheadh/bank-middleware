<?php

namespace App\Model\Entity\Transaction;

use App\Entity\Transaction\Transaction;
use Doctrine\Common\Collections\Collection;

/**
 * Entity mapped with transactions (one to many).
 */
interface TransactionEntityMapInterface
{

    /**
     * Add a transaction.
     *
     * @param Transaction $transaction Added transaction.
     *
     * @return $this
     */
    public function addTransaction(Transaction $transaction): self;

    /**
     * Remove a transaction.
     *
     * @param Transaction $transaction Removed transaction.
     *
     * @return $this
     */
    public function removeTransaction(Transaction $transaction): self;

    /**
     * Get transactions.
     *
     * @return Collection
     */
    public function getTransactions(): Collection;

}