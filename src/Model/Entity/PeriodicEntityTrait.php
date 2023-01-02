<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with periodicity trait.
 */
trait PeriodicEntityTrait
{

    /**
     * Entity periodicity.
     *
     * @var string
     */
    #[ORM\Column(
      type: 'string',
      length: 100,
      nullable: true,
    )]
    private ?string $periodicity = null;

    /**
     * {@inheritDoc}
     */
    public function getPeriodicity(): ?string
    {
        return $this->periodicity;
    }

    /**
     * {@inheritDoc}
     */
    public function setPeriodicity(?string $periodicity): self
    {
        $this->periodicity = $periodicity;
        return $this;
    }
}