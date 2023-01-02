<?php

namespace App\Model\Entity;

/**
 * Entity with balance.
 */
interface BalancedEntityInterface
{

    /**
     * Add given amount to the current balance.
     *
     * @param float $amount Added amount.
     *
     * @return $this
     */
    public function addBalance(float $amount): self;

    /**
     * Get entity balance.
     *
     * @return float
     */
    public function getBalance(): float;

    /**
     * Reduce given amount to the current balance.
     *
     * @param float $amount Reduced amount.
     *
     * @return $this
     */
    public function reduceBalance(float $amount): self;

    /**
     * Set entity balance.
     *
     * @param float $balance Entity balance.
     *
     * @return $this
     */
    public function setBalance(float $balance): self;
}