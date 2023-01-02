<?php

namespace App\Model\Entity;

/**
 * Simple entity.
 */
interface EntityInterface
{
    /**
     * Get primary key.
     */
    public function getId(): ?int;
}