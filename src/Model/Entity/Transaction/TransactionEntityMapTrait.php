<?php

namespace App\Model\Entity\Transaction;

use App\Entity\Transaction\Transaction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Entity mapped with transactions (one to many) trait.
 */
trait TransactionEntityMapTrait
{

    /**
     * Entity transactions.
     *
     * @var Collection<Transaction>
     */
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $this->setTransactionMapping($transaction);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
        }

        $this->removeTransactionMapping($transaction);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * Get transaction mapped field name.
     *
     * @return string|null
     */
    protected function getTransactionMappedFieldName(): ?string
    {
        try {
            $property = new ReflectionProperty($this, 'transactions');
            /** @var ORM\OneToMany|null $attribute */
            $attribute = current(
              $property->getAttributes(ORM\OneToMany::class)
            );

            return $attribute?->mappedBy ?? null;
        } catch (ReflectionException) {
        }

        return null;
    }

    /**
     * Unmap transaction from the current entity.
     *
     * @param Transaction $transaction Unmapped entity.
     *
     * @return void
     */
    protected function removeTransactionMapping(Transaction $transaction): void
    {
        $field = $this->getTransactionMappedFieldName();

        if ($field !== null) {
            $accessor = PropertyAccess::createPropertyAccessor();

            if ($accessor->getValue($transaction, $field) === $this) {
                $accessor->setValue($transaction, $field, null);
            }
        }
    }

    /**
     * Map transaction to the current entity.
     *
     * @param Transaction $transaction Mapped transaction.
     *
     * @return void
     */
    protected function setTransactionMapping(Transaction $transaction): void
    {
        $field = $this->getTransactionMappedFieldName();

        if ($field !== null) {
            $accessor = PropertyAccess::createPropertyAccessor();

            if ($accessor->getValue($transaction, $field) !== $this) {
                $accessor->setValue($transaction, $field, $this);
            }
        }
    }

}