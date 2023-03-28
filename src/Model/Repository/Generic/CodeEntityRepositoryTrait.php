<?php

namespace App\Model\Repository\Generic;

/**
 * Entity with code repository trait.
 */
trait CodeEntityRepositoryTrait
{

    /**
     * {@inheritDoc}
     */
    public function findOneByCode(?string $code): ?object
    {
        return $this->findOneBy(['code' => $code]);
    }
}