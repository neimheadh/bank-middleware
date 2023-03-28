<?php

namespace App\Model\Repository\Generic;

/**
 * Entity with code repository.
 *
 * @template T
 */
interface CodeEntityRepositoryInterface
{

    /**
     * Find entity by code.
     *
     * @param string|null $code Entity code.
     *
     * @return T|null
     */
    public function findOneByCode(?string $code): ?object;
}