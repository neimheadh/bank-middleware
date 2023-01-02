<?php

namespace App\Entity\Localization;

use App\Model\Entity\DatedEntityInterface;
use App\Model\Entity\DatedEntityTrait;
use App\Model\Entity\EntityInterface;
use App\Model\Entity\EntityTrait;
use App\Model\Entity\NamedEntityInterface;
use App\Model\Entity\NamedEntityTrait;
use App\Repository\Localization\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Currency.
 */
#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ORM\Table(name: 'localization_currency')]
class Currency implements EntityInterface,
                          DatedEntityInterface,
                          NamedEntityInterface
{

    use EntityTrait;
    use DatedEntityTrait;
    use NamedEntityTrait;

    /**
     * Currency char.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 5)]
    private ?string $char = null;

    /**
     * Currency name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $name = null;

    /**
     * Currency ISO code.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 5, unique: true)]
    private ?string $iso = null;

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return "$this->iso";
    }

    /**
     * Get currency char.
     *
     * @return string|null
     */
    public function getChar(): ?string
    {
        return $this->char;
    }

    /**
     * Set currency char.
     *
     * @param string|null $char Currency char.
     *
     * @return $this
     */
    public function setChar(?string $char): self
    {
        $this->char = $char;

        return $this;
    }

    /**
     * Get currency ISO code.
     *
     * @return string|null
     */
    public function getIso(): ?string
    {
        return $this->iso;
    }

    /**
     * Set currency ISO code.
     *
     * @param string|null $iso Currency ISO code.
     *
     * @return $this
     */
    public function setIso(?string $iso): self
    {
        $this->iso = $iso;

        return $this;
    }
}