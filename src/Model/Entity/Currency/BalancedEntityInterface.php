<?php

namespace App\Model\Entity\Currency;

use App\Entity\Currency\Currency;
use App\Model\Entity\Currency\Link\CurrencyManyToOneInterface;

/**
 * Entity with balance.
 */
interface BalancedEntityInterface extends CurrencyManyToOneInterface
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
     * @param Currency|null $currency Returned balance currency.
     *
     * @return float|null
     */
    public function getBalance(?Currency $currency = null): ?float;

    /**
     * Set entity balance.
     *
     * @param float|null    $balance  Entity balance.
     * @param Currency|null $currency Given balance currency.
     *
     * @return $this
     */
    public function setBalance(
        ?float $balance,
        ?Currency $currency = null
    ): self;

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