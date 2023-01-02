<?php

namespace App\Entity\User;

use App\Model\Entity\EntityInterface;
use App\Model\Entity\EntityTrait;
use App\Model\Entity\User\OwnedEntityInterface;
use App\Model\Entity\User\OwnedEntityTrait;
use App\Repository\User\AccessListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * User access list.
 */
#[ORM\Entity(repositoryClass: AccessListRepository::class)]
#[ORM\Table(name: 'user_access_list')]
class AccessList implements EntityInterface,
                            OwnedEntityInterface
{

    use EntityTrait;
    use OwnedEntityTrait;

    /**
     * Read access.
     *
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $readable = false;

    /**
     * Remove access.
     *
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $removable = false;

    /**
     * Write access.
     *
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $writeable = false;

    /**
     * Is remove access granted?
     *
     * @return bool
     */
    public function isRemovable(): bool
    {
        return $this->removable;
    }

    /**
     * Is read access granted?
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * Is write access granted?
     *
     * @return bool
     */
    public function isWriteable(): bool
    {
        return $this->writeable;
    }

    /**
     * Set remove access.
     *
     * @param bool $status True if removable.
     *
     * @return $this
     */
    public function setRemovable(bool $status): self
    {
        $this->removable = $status;

        return $this;
    }

    /**
     * Set read access.
     *
     * @param bool $status True if readable.
     *
     * @return $this
     */
    public function setReadable(bool $status): self
    {
        $this->readable = $status;

        return $this;
    }

    /**
     * Set write access.
     *
     * @param bool $status True if writable.
     *
     * @return $this
     */
    public function setWriteable(bool $status): self
    {
        $this->writeable = $status;

        return $this;
    }
}