<?php

namespace App\Model\Entity\Account\Link;

use App\Entity\Account\Account;

/**
 * Account many-to-one entity.
 */
interface AccountManyToOneInterface
{
    /**
     * Get account.
     *
     * @return Account|null
     */
    public function getAccount(): ?Account;

    /**
     * Set account.
     *
     * @param Account|null $account Account.
     *
     * @return self
     */
    public function setAccount(?Account $account): self;
}