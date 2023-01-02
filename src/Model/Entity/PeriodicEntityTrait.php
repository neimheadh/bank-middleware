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
     * @var int
     */
    #[ORM\Column(
      type: 'smallint',
      options: ['default' => PeriodicEntityInterface::UNKNOWN]
    )]
    private int $periodicity = PeriodicEntityInterface::UNKNOWN;

    /**
     * {@inheritDoc}
     */
    public function getPeriodicity(): int
    {
        return $this->periodicity;
    }

    /**
     * {@inheritDoc}
     */
    public function setPeriodicity(int $periodicity): self
    {
        $this->periodicity = $periodicity;
        return $this;
    }
}