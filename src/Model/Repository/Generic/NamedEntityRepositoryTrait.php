<?php

namespace App\Model\Repository\Generic;

/**
 * Entity with name repository trait.
 */
trait NamedEntityRepositoryTrait
{

    /**
     * {@inheritDoc}
     */
    public function findByName(?string $name): array
    {
        return $this->findBy(['name' => $name]);
    }

    /**
     * {@inheritDoc}
     */
    public function findOneByName(?string $name): ?object
    {
        return $this->findOneBy(['name' => $name]);
    }
}