<?php

namespace App\Model\Entity\Currency;

use App\Entity\Currency\Currency;
use App\Model\Entity\Currency\Link\CurrencyManyToOneInterface;

/**
 * Entity with balance.
 */
interface BalancedManyToOneInterface extends CurrencyManyToOneInterface
{

    /**
     * Add value to the current balance.
     *
     * @param float         $balance  Added value.
     * @param Currency|null $currency Added balance currency.
     *
     * @return $this
     */
    public function addBalance(
        float $balance,
        ?Currency $currency = null
    ): self;

    /**
     * Get entity balance.
     *
     * @return float|null
     */
    public function getBalance(): ?float;

    /**
     * Set entity balance.
     *
     * @param float|null $balance Entity balance.
     *
     * @return $this
     */
    public function setBalance(?float $balance): self;

    /**
     * Subtract value to the balance.
     *
     * @param float         $balance  Subtracted value.
     * @param Currency|null $currency Added balance currency.
     *
     * @return $this
     */
    public function subtractBalance(
        float $balance,
        ?Currency $currency = null
    ): self;

}