<?php

namespace App\Model\Entity\Generic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application entity trait.
 */
trait EntityTrait
{
    use DatedEntityTrait;

    /**
     * Entity id.
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    /**
     * {@inheritDoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

}