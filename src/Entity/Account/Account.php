<?php

namespace App\Entity\Account;

use App\Event\ORM\EntityListener\Account\AccountEntityListener;
use App\Model\Entity\Currency\BalancedEntityInterface;
use App\Model\Entity\Currency\BalancedEntityTrait;
use App\Model\Entity\Generic\CodeEntityInterface;
use App\Model\Entity\Generic\CodeEntityTrait;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Repository\Account\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\NamedEntityInterface;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\NamedEntityTrait;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Bank account.
 */
#[Sonata\Admin(
    formFields: [
        'code' => new Sonata\FormField(),
        'name' => new Sonata\FormField(),
        'balance' => new Sonata\FormField(),
        'currency' => new Sonata\FormField(
            options: ['choice_label' => 'name', 'required' => false]
        ),
    ],
    listFields: [
        'code' => new Sonata\ListField(),
        'name' => new Sonata\ListField(),
        'balance' => new Sonata\ListField(type: 'balance'),
        'futureBalance' => new Sonata\ListField(type: 'balance'),
    ],
    showFields: [
        'transactions' => new Sonata\ShowField(),
    ]
)]
#[Sonata\AddChild(class: Transaction::class, field: 'account')]
#[Sonata\ListAction(name: 'edit')]
#[Sonata\ListAction(name: 'account_transaction_list', options: [
    'template' => 'list/Account/transactions_actions.html.twig',
])]
#[Sonata\ListAction(name: 'delete')]
#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\EntityListeners([AccountEntityListener::class])]
#[ORM\Table(name: 'app_account_account')]
class Account implements EntityInterface,
                         NamedEntityInterface,
                         BalancedEntityInterface,
                         CodeEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use BalancedEntityTrait;
    use CodeEntityTrait;

    /**
     * Balance when all transactions will be processed.
     *
     * The value is calculated and set by the
     *
     * @var float
     */
    private float $futureBalance = 0.0;

    /**
     * Transaction list.
     *
     * @var Collection<Transaction>
     */
    #[ORM\OneToMany(
        mappedBy: 'account',
        targetEntity: Transaction::class,
        cascade: ['persist'],
    )]
    private Collection $transactions;

    public function __construct()
    {
        $this->balance = 0.0;
        $this->transactions = new ArrayCollection();
    }

    /**
     * Get balance when all transactions will be processed.
     *
     * @return float
     */
    public function getFutureBalance(): float
    {
        return $this->futureBalance;
    }

    /**
     * Set balance when all transaction will be processed.
     *
     * @param float $futureBalance Balance when all transaction
     *
     * @return $this
     */
    public function setFutureBalance(float $futureBalance): self
    {
        $this->futureBalance = $futureBalance;

        return $this;
    }

    /**
     * Get transactions.
     *
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * Add transaction.
     *
     * @param Transaction $transaction Added transaction.
     *
     * @return $this
     */
    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setAccount($this);

            if ($transaction->isProcessed()) {
                $this->futureBalance += $transaction->getBalance(
                    $this->currency
                );
            }
        }

        return $this;
    }

    /**
     * Remove transaction.
     *
     * @param Transaction $transaction Removed transaction.
     *
     * @return $this
     */
    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getAccount() === $this) {
                $transaction->setAccount(null);
            }

            if ($transaction->isProcessed()) {
                $this->futureBalance -= $transaction->getBalance(
                    $this->currency
                );
            }
        }

        return $this;
    }


}