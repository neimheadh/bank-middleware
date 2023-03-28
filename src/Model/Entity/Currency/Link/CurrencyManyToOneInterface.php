<?php

namespace App\Model\Entity\Currency\Link;

use App\Entity\Currency\Currency;

/**
 * Entity with currency.
 */
interface CurrencyManyToOneInterface
{

    /**
     * Get entity currency.
     *
     * @return Currency|null
     */
    public function getCurrency(): ?Currency;

    /**
     * Set entity currency.
     *
     * @param Currency|null $currency Entity currency.
     *
     * @return $this
     */
    public function setCurrency(?Currency $currency): self;
}