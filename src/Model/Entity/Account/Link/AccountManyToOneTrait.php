<?php

namespace App\Model\Entity\Account\Link;

use App\Entity\Account\Account;
use Doctrine\ORM\Mapping as ORM;

/**
 * Account many-to-one entity trait.
 */
trait AccountManyToOneTrait
{

    /**
     * Entity account.
     *
     * @var Account|null
     */
    #[ORM\ManyToOne(targetEntity: Account::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'account_id', nullable: false, onDelete: 'CASCADE')]
    private ?Account $account = null;

    /**
     * {@inheritDoc}
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * {@inheritDoc}
     */
    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}