<?php

namespace App\Model\Entity\Localization;

use App\Entity\Localization\Currency;

/**
 * Entity with currency.
 */
interface CurrencyEntityMapInterface
{

    /**
     * Get currency.
     *
     * @return Currency|null
     */
    public function getCurrency(): ?Currency;

    /**
     * Set currency.
     *
     * @param Currency|null $currency Entity currency.
     *
     * @return $this
     */
    public function setCurrency(?Currency $currency): self;
}