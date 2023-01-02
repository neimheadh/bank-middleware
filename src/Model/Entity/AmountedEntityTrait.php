<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with amount trait.
 */
trait AmountedEntityTrait
{

    /**
     * Entity amount.
     *
     * @var float
     */
    #[ORM\Column(type: 'float')]
    private float $amount = 0.0;

    /**
     * {@inheritDoc}
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * {@inheritDoc}
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
}