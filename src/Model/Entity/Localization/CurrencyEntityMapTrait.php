<?php

namespace App\Model\Entity\Localization;

use App\Entity\Localization\Currency;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with currency trait.
 */
trait CurrencyEntityMapTrait
{

    /**
     * Entity currency.
     *
     * @var Currency|null
     */
    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'currency_id', nullable: false)]
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