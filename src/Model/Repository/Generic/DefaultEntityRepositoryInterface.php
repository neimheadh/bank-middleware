<?php

namespace App\Model\Repository\Generic;

/**
 * Entity with default switch repository.
 *
 * @template T
 */
interface DefaultEntityRepositoryInterface
{

    /**
     * Find default entity.
     *
     * @return object|null
     */
    public function findDefault(): ?object;
}