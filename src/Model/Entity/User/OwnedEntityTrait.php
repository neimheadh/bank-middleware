<?php

namespace App\Model\Entity\User;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with owner trait.
 */
trait OwnedEntityTrait
{

    /**
     * Entity owner.
     *
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', nullable: false, onDelete: 'CASCADE')]
    private ?User $owner = null;

    /**
     * {@inheritDoc}
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * {@inheritDoc}
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}