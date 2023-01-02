<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Balanced entity trait.
 */
trait BalancedEntityTrait
{

    /**
     * Entity balance.
     *
     * @var float
     */
    #[ORM\Column(type: 'float', options: ['default' => 0.0])]
    private float $balance = 0.0;

    /**
     * {@inheritDoc}
     */
    public function addBalance(float $amount): self
    {
        $this->balance += $amount;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * {@inheritDoc}
     */
    public function reduceBalance(float $amount): self
    {
        $this->balance -= $amount;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

}