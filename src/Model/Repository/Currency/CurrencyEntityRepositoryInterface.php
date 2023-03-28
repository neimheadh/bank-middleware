<?php

namespace App\Model\Repository\Currency;

use App\Entity\Currency\Currency;

/**
 * Entity with currency repository.
 *
 * @template T
 */
interface CurrencyEntityRepositoryInterface
{

    /**
     * Find entities with the given currency.
     *
     * @param Currency|null $currency Currency.
     *
     * @return array
     */
    public function findByCurrency(?Currency $currency): array;
}