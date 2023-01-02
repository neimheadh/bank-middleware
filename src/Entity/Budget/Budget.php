<?php

namespace App\Entity\Budget;

use App\Entity\Transaction\Transaction;
use App\Model\Entity\AmountedEntityInterface;
use App\Model\Entity\AmountedEntityTrait;
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
use App\Model\Entity\PeriodicEntityTrait;
use App\Model\Entity\RefreshedEntityInterface;
use App\Model\Entity\RefreshedEntityTrait;
use App\Model\Entity\StartEndDateEntityInterface;
use App\Model\Entity\StartEndDateEntityTrait;
use App\Model\Entity\Transaction\TransactionEntityMapInterface;
use App\Model\Entity\Transaction\TransactionEntityMapTrait;
use App\Model\Entity\User\OwnedEntityInterface;
use App\Model\Entity\User\OwnedEntityTrait;
use App\Repository\Budget\BudgetRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Budget.
 */
#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\Table(name: 'budget_budget')]
class Budget implements EntityInterface,
                        DatedEntityInterface,
                        NamedEntityInterface,
                        BalancedEntityInterface,
                        TransactionEntityMapInterface,
                        StartEndDateEntityInterface,
                        CurrencyEntityMapInterface,
                        AmountedEntityInterface,
                        RefreshedEntityInterface,
                        OwnedEntityInterface
{

    use EntityTrait;
    use DatedEntityTrait;
    use NamedEntityTrait;
    use BalancedEntityTrait;
    use TransactionEntityMapTrait {
        TransactionEntityMapTrait::__construct as private _initTransactions;
    }
    use StartEndDateEntityTrait;
    use CurrencyEntityMapTrait;
    use AmountedEntityTrait;
    use PeriodicEntityTrait;
    use StartEndDateEntityTrait;
    use RefreshedEntityTrait {
        RefreshedEntityTrait::__construct as private _initRefreshed;
    }
    use OwnedEntityTrait;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(
      mappedBy: 'budget',
      targetEntity: Transaction::class,
      cascade: ['persist']
    )]
    private Collection $transactions;

    public function __construct()
    {
        $this->_initRefreshed();
        $this->_initTransactions();
    }

    /**
     * {@inheritDoc}
     */
    public function refresh(): void
    {
        // Re-initialize budget balance.
        $this->balance = $this->amount;
    }

}