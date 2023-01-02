<?php

namespace App\Entity\Transaction;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Account\Account;
use App\Entity\Budget\Budget;
use App\Entity\Dealer\Dealer;
use App\Entity\User\User;
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
use App\Model\Entity\UidEntityInterface;
use App\Model\Entity\UidEntityTrait;
use App\Model\Entity\User\OwnedEntityInterface;
use App\Model\Entity\User\OwnedEntityTrait;
use App\Repository\Transaction\TransactionRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Account transaction.
 */
#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: 'transaction_transaction')]
#[ApiResource]
class Transaction implements EntityInterface,
                             NamedEntityInterface,
                             BalancedEntityInterface,
                             UidEntityInterface,
                             DatedEntityInterface,
                             CurrencyEntityMapInterface,
                             OwnedEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use BalancedEntityTrait;
    use UidEntityTrait;
    use DatedEntityTrait;
    use CurrencyEntityMapTrait;
    use OwnedEntityTrait;

    /**
     * Transaction account.
     *
     * @var Account|null
     */
    #[ORM\ManyToOne(
      targetEntity: Account::class,
      cascade: ['persist'],
      inversedBy: 'transactions',
    )]
    #[ORM\JoinColumn(
      name: 'account_id',
      nullable: false,
      onDelete: 'CASCADE',
    )]
    private ?Account $account = null;

    /**
     * Transaction budget.
     *
     * @var Budget|null
     */
    #[ORM\ManyToOne(
      targetEntity: Budget::class,
      cascade: ['persist'],
      inversedBy: 'transactions',
    )]
    #[ORM\JoinColumn(
      name: 'budget_id',
      onDelete: 'SET NULL',
    )]
    private ?Budget $budget = null;

    /**
     * Transaction dealer.
     *
     * @var Dealer|null
     */
    #[ORM\ManyToOne(
      targetEntity: Dealer::class,
      cascade: ['persist'],
      inversedBy: 'transactions',
    )]
    #[ORM\JoinColumn(
      name: 'dealer_id',
      onDelete: 'SET NULL',
    )]
    private ?Dealer $dealer = null;

    /**
     * Date the transaction was recorded.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $recordDate = null;

    /**
     * Date the transaction was effective.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $transactionDate = null;

    /**
     * Get transaction account.
     *
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * Set transaction account.
     *
     * @param Account|null $account Transaction account.
     *
     * @return $this
     */
    public function setAccount(?Account $account): self
    {
        if ($this->account !== $account) {
            $this->account = $account;
            $account?->addTransaction($this);
        }

        return $this;
    }

    /**
     * Get record date.
     *
     * @return DateTimeInterface|null
     */
    public function getRecordDate(): ?DateTimeInterface
    {
        return $this->recordDate;
    }

    /**
     * Set record date.
     *
     * @param DateTimeInterface|null $recordDate Record date.
     *
     * @return $this
     */
    public function setRecordDate(?DateTimeInterface $recordDate): self
    {
        $this->recordDate = $recordDate;

        return $this;
    }

    /**
     * Get transaction date.
     *
     * @return DateTimeInterface|null
     */
    public function getTransactionDate(): ?DateTimeInterface
    {
        return $this->transactionDate;
    }

    /**
     * Set transaction date.
     *
     * @param DateTimeInterface|null $transactionDate Transaction date.
     *
     * @return $this
     */
    public function setTransactionDate(
      ?DateTimeInterface $transactionDate
    ): self {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get dealer.
     *
     * @return Dealer|null
     */
    public function getDealer(): ?Dealer
    {
        return $this->dealer;
    }

    /**
     * Set dealer.
     *
     * @param Dealer|null $dealer Transaction dealer.
     *
     * @return $this
     */
    public function setDealer(?Dealer $dealer): self
    {
        if ($this->dealer !== $dealer) {
            $this->dealer = $dealer;
            $dealer->addTransaction($this);
        }

        return $this;
    }

    /**
     * Get transaction budget.
     *
     * @return Budget|null
     */
    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    /**
     * Set transaction budget.
     *
     * @param Budget|null $budget Transaction budget.
     *
     * @return $this;
     */
    public function setBudget(?Budget $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

}