<?php

namespace App\Entity\Currency;

use App\Model\Entity\Generic\CodeEntityInterface;
use App\Model\Entity\Generic\CodeEntityTrait;
use App\Model\Entity\Generic\DefaultEntityInterface;
use App\Model\Entity\Generic\DefaultEntityTrait;
use App\Model\Entity\Generic\PluralNameEntityInterface;
use App\Model\Entity\Generic\PluralNameEntityTrait;
use App\Repository\Currency\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SolidBundle\Doctrine\Entity\Date\DatedEntityInterface;
use Neimheadh\SolidBundle\Doctrine\Entity\Date\DatedEntityTrait;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Currency.
 */
#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ORM\Table(name: 'app_currency_currency')]
#[Sonata\Admin(
    group: 'Settings',
    formFields: [
        'code' => new Sonata\FormField(),
        'name' => new Sonata\FormField(),
        'pluralName' => new Sonata\FormField(),
        'default' => new Sonata\FormField(),
        'symbol' => new Sonata\FormField(),
        'nativeSymbol' => new Sonata\FormField(),
        'rounded' => new Sonata\FormField(),
        'decimalDigits' => new Sonata\FormField(),
        'usdExchangeRate' => new Sonata\FormField(),
    ],
    listFields: [
        'code' => new Sonata\ListField(),
        'name' => new Sonata\ListField(),
    ]
)]
class Currency implements DatedEntityInterface,
                          CodeEntityInterface,
                          DefaultEntityInterface,
                          PluralNameEntityInterface
{

    use DatedEntityTrait;
    use CodeEntityTrait;
    use DefaultEntityTrait;
    use PluralNameEntityTrait;

    /**
     * Default switch.
     *
     * @var bool
     */
    #[ORM\Column(
        name: 'is_default',
        type: 'boolean',
        unique: true,
        nullable: true
    )]
    private ?bool $default = null;

    /**
     * Currency code.
     *
     * @var string|null
     */
    #[ORM\Id]
    #[ORM\Column(
        type: 'string',
        length: 8,
        unique: true,
        options: ['fixed' => true],
    )]
    private ?string $code = null;

    /**
     * Currency decimal digits.
     *
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $decimalDigits = null;

    /**
     * Currency symbol in native language.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 8, nullable: true)]
    private ?string $nativeSymbol = null;

    /**
     * Rounded digits.
     *
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $rounded = null;

    /**
     * Currency symbol.
     *
     * @var string|null
     */
    #[ORM\Column(
        type: 'string',
        length: 8,
        nullable: true
    )]
    private ?string $symbol = null;

    /**
     * USD exchange rate.
     *
     * @var float|null
     */
    #[ORM\Column(
        name: 'usd_exchange_rate',
        type: 'float',
        nullable: true
    )]
    private ?float $usdExchangeRate = null;

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return "$this->symbol";
    }

    /**
     * Convert value from the given currency to the current currency.
     *
     * @param Currency $currency Value currency.
     * @param float    $value    Converted value.
     *
     * @return float
     */
    public function convert(self $currency, float $value): float
    {
        $exchange = $currency->usdExchangeRate ?: 1;
        $selfExchange = $this->usdExchangeRate ?: 1;

        return $this->round(($value * $exchange) / $selfExchange);
    }

    /**
     * Get number of decimal digits.
     *
     * @return int|null
     */
    public function getDecimalDigits(): ?int
    {
        return $this->decimalDigits;
    }

    /**
     * Get symbol in native language.
     *
     * @return string|null
     */
    public function getNativeSymbol(): ?string
    {
        return $this->nativeSymbol;
    }

    /**
     * Get rounded digit count.
     *
     * @return int|null
     */
    public function getRounded(): ?int
    {
        return $this->rounded;
    }

    /**
     * Get currency symbol.
     *
     * @return string|null
     */
    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    /**
     * Get USD exchange rate.
     *
     * @return float|null
     */
    public function getUsdExchangeRate(): ?float
    {
        return $this->usdExchangeRate;
    }

    /**
     * Round given value.
     *
     * @param float $value Rounded value.
     *
     * @return float
     */
    public function round(float $value): float
    {
        $p = $this->rounded === null
            ? 1
            : pow(10, $this->rounded);

        return $this->rounded === null
            ? $value
            : round($value * $p) / $p;
    }

    /**
     * Set decimal digits count.
     *
     * @param int|null $decimalDigits Decimal digits count.
     *
     * @return $this
     */
    public function setDecimalDigits(?int $decimalDigits): self
    {
        $this->decimalDigits = $decimalDigits;

        return $this;
    }

    /**
     * Set symbol in native language.
     *
     * @param string|null $nativeSymbol Symbol in native language.
     *
     * @return $this
     */
    public function setNativeSymbol(?string $nativeSymbol): self
    {
        $this->nativeSymbol = $nativeSymbol;

        return $this;
    }

    /**
     * Set rounded digits count.
     *
     * @param int|null $rounded Rounded digits count.
     *
     * @return $this
     */
    public function setRounded(?int $rounded): self
    {
        $this->rounded = $rounded;

        return $this;
    }

    /**
     * Set currency symbol.
     *
     * @param string|null $symbol Currency symbol.
     *
     * @return $this
     */
    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Set USD exchange rate.
     *
     * @param float|null $rate USD exchange rate.
     *
     * @return $this
     */
    public function setUsdExchangeRate(?float $rate): self
    {
        $this->usdExchangeRate = $rate;

        return $this;
    }

}