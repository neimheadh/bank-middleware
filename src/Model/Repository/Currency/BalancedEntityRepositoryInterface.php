<?php

namespace App\Model\Repository\Currency;

/**
 * Balanced entity repository.
 *
 * @template T
 */
interface BalancedEntityRepositoryInterface extends
    CurrencyEntityRepositoryInterface
{

    /**
     * Find entities with given balance.
     *
     * @param float|null $balance Balance.
     *
     * @return array<T>
     */
    public function findByBalance(?float $balance): array;

}