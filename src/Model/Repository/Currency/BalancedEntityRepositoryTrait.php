<?php

namespace App\Model\Repository\Currency;

/**
 * Balanced entity repository trait.
 */
trait BalancedEntityRepositoryTrait
{
    use CurrencyEntityRepositoryTrait;

    /**
     * {@inheritDoc}
     */
    public function findByBalance(?float $balance): array
    {
        return $this->findBy(['balance' => $balance]);
    }
}