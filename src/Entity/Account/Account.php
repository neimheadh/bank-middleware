<?php

namespace App\Entity\Account;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Transaction\Transaction;
use App\Model\Entity\BalancedEntityInterface;
use App\Model\Entity\BalancedEntityTrait;
use App\Model\Entity\DatedEntityInterface;
use App\Model\Entity\DatedEntityTrait;
use App\Model\Entity\EntityInterface;
use App\Model\Entity\EntityTrait;
use App\Model\Entity\Localization\CurrencyEntityMapInterface;
use App\Model\Entity\Localization\CurrencyEntityMapTrait;
use App\Model\Entity\NamedEntityInterface;
use App\Model\Entity\NamedEntityTrait;
use App\Model\Entity\Transaction\TransactionEntityMapInterface;
use App\Model\Entity\Transaction\TransactionEntityMapTrait;
use App\Model\Entity\User\OwnedEntityInterface;
use App\Model\Entity\User\OwnedEntityTrait;
use App\Repository\Account\AccountRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Bank account.
 */
#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'account_account')]
#[ApiResource]
class Account implements EntityInterface,
                         NamedEntityInterface,
                         BalancedEntityInterface,
                         DatedEntityInterface,
                         CurrencyEntityMapInterface,
                         TransactionEntityMapInterface,
                         OwnedEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use BalancedEntityTrait;
    use DatedEntityTrait;
    use CurrencyEntityMapTrait;
    use TransactionEntityMapTrait;
    use OwnedEntityTrait;

    /**
     * Account transactions.
     *
     * @var Collection<Transaction>
     */
    #[ORM\OneToMany(
      mappedBy: 'account',
      targetEntity: Transaction::class,
      cascade: ['persist', 'remove'],
    )]
    private Collection $transactions;

}