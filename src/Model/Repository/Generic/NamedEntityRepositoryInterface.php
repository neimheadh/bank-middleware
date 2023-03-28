<?php

namespace App\Model\Repository\Generic;

/**
 * Named entity repository.
 *
 * @template T
 */
interface NamedEntityRepositoryInterface
{

    /**
     * Find entities by name.
     *
     * @param string|null $name Entity name.
     *
     * @return T[]
     */
    public function findByName(?string $name): array;

    /**
     * Find one entity by name.
     *
     * @param string|null $name Entity name.
     *
     * @return T|null
     */
    public function findOneByName(?string $name): ?object;
}