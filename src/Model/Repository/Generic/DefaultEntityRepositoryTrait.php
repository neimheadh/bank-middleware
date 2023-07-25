<?php

namespace App\Model\Repository\Generic;

/**
 * Entity with default switch repository trait.
 */
trait DefaultEntityRepositoryTrait
{

    /**
     * {@inheritDoc}
     */
    public function findDefault(): ?object
    {
        return $this->findOneBy(['isDefault' => true]);
    }
}