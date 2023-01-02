<?php

namespace App\Model\Entity;

/**
 * Entity with amount.
 */
interface AmountedEntityInterface
{

    /**
     * Get entity amount.
     *
     * @return float
     */
    public function getAmount(): float;

    /**
     * Set entity amount.
     *
     * @param float $amount Entity amount.
     *
     * @return $this
     */
    public function setAmount(float $amount): self;
}