<?php

namespace App\Model\Entity\Currency\Link;

use App\Entity\Currency\Currency;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with currency trait.
 */
trait CurrencyManyToOneTrait
{

    /**
     * Entity currency.
     *
     * @var Currency|null
     */
    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(
        name: 'currency_id',
        referencedColumnName: 'code',
        nullable: false
    )]
    private ?Currency $currency = null;

    /**
     * {@inheritDoc}
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}