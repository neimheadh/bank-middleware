<?php

namespace App\Model\Repository\Currency;

use App\Entity\Currency\Currency;

/**
 * Entity with currency repository trait.
 */
trait CurrencyEntityRepositoryTrait
{

    /**
     * {@inheritDoc}
     */
    public function findByCurrency(?Currency $currency): array
    {
        return $this->findBy(['currency' => $currency]);
    }
}