<?php

namespace App\Block\Account;

use App\Block\BlockServiceInterface;

class AccountBalanceBlock extends AbstractAccountBlock implements
    BlockServiceInterface
{

    /**
     * {@inheritDoc}
     */
    protected function getTemplate(): string
    {
        return 'block/Account/account_balance.block.html.twig';
    }

    /**
     * {@inheritDoc}
     */
    protected function getTitle(): string
    {
        return 'Account.block.account_balance.title';
    }

}