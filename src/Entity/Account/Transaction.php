<?php

namespace App\Entity\Account;

use App\Model\Entity\Account\Link\AccountManyToOneInterface;
use App\Model\Entity\Account\Link\AccountManyToOneTrait;
use App\Model\Entity\Currency\BalancedEntityTrait;
use App\Model\Entity\Currency\BalancedManyToOneInterface;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\Generic\NamedEntityInterface;
use App\Model\Entity\Generic\NamedEntityTrait;
use App\Repository\Account\TransactionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;
use Sonata\Form\Type\DatePickerType;

/**
 * Account transaction.
 */
#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: 'app_account_transaction')]
#[Sonata\Admin(
    formFields: [
        'account' => new Sonata\FormField(),
        'transactionDate' => new Sonata\FormField(
            type: DatePickerType::class
        ),
        'writeDate' => new Sonata\FormField(
            type: DatePickerType::class
        ),
        'name' => new Sonata\FormField(),
        'balance' => new Sonata\FormField(),
        'currency' => new Sonata\FormField(),
    ],
    listFields: [
        'transactionDate' => new Sonata\ListField(),
        'writeDate' => new Sonata\ListField(),
        'name' => new Sonata\ListField(),
        'balance' => new Sonata\ListField(
            type: 'balance',
            fieldDescriptionOptions: [
                'currency_field' => 'currency',
            ]
        ),
    ],
)]
class Transaction implements EntityInterface,
                             NamedEntityInterface,
                             AccountManyToOneInterface,
                             BalancedManyToOneInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use AccountManyToOneTrait;
    use BalancedEntityTrait;

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
    #[ORM\JoinColumn(name: 'account_id', nullable: false, onDelete: 'CASCADE')]
    private ?Account $account = null;

    /**
     * Transaction due date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(
        name: 'due_date',
        type: 'datetime',
        nullable: true
    )]
    private ?DateTimeInterface $writeDate = null;

    /**
     * Transaction date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(
        name: 'transaction_date',
        type: 'datetime',
        options: ['default' => 'CURRENT_TIMESTAMP'])
    ]
    private ?DateTimeInterface $transactionDate = null;


    public function __construct()
    {
        $this->transactionDate = new DateTime();
    }

    /**
     * Get due date.
     *
     * @return DateTimeInterface|null
     */
    public function getWriteDate(): ?DateTimeInterface
    {
        return $this->writeDate;
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
     * Set due date.
     *
     * @param DateTimeInterface|null $writeDate Due date.
     *
     * @return $this
     */
    public function setWriteDate(?DateTimeInterface $writeDate): self
    {
        $this->writeDate = $writeDate;

        return $this;
    }

    /**
     * Set transaction date.
     *
     * @param DateTimeInterface|null $transactionDate Transction date.
     *
     * @return $this
     */
    public function setTransactionDate(
        ?DateTimeInterface $transactionDate
    ): self {
        $this->transactionDate = $transactionDate;

        return $this;
    }

}