<?php

namespace App\Model\Entity\Currency;

use App\Entity\Currency\Currency;
use App\Model\Entity\Currency\Link\CurrencyManyToOneTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with balance trait.
 */
trait BalancedEntityTrait
{

    use CurrencyManyToOneTrait;

    /**
     * Entity balance.
     *
     * @var float|null
     */
    #[ORM\Column(type: 'float', options: ['default' => 0.0])]
    private ?float $balance = null;

    /**
     * {@inheritDoc}
     */
    public function addBalance(
        float $balance,
        ?Currency $currency = null
    ): self {
        $this->balance += $currency
            ? $this->currency->convert($currency, $balance)
            : $balance;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBalance(): ?float
    {
        return $this->balance;
    }

    /**
     * {@inheritDoc}
     */
    public function setBalance(?float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function subtractBalance(
        float $balance,
        ?Currency $currency = null
    ): self {
        $this->balance -= $currency
            ? $this->currency->convert($currency, $balance)
            : $balance;

        return $this;
    }

}