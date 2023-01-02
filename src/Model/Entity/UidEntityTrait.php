<?php

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with string uid trait.
 */
trait UidEntityTrait
{

    /**
     * Entity uid.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 128, unique: true, nullable: true)]
    private ?string $uid = null;

    /**
     * {@inheritDoc}
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * {@inheritDoc}
     */
    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}