<?php

namespace App\Entity\Account;

use App\Event\ORM\EntityListener\Account\TransactionEntityListener;
use App\Model\Entity\Account\Link\AccountManyToOneInterface;
use App\Model\Entity\Account\Link\AccountManyToOneTrait;
use App\Model\Entity\Budget\Link\BudgetManyToOneInterface;
use App\Model\Entity\Budget\Link\BudgetManyToOneTrait;
use App\Model\Entity\Currency\BalancedEntityInterface;
use App\Model\Entity\Currency\BalancedEntityTrait;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\ThirdParty\Link\ThirdPartyManyToOneInterface;
use App\Model\Entity\ThirdParty\Link\ThirdPartyManyToOneTrait;
use App\Repository\Account\TransactionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\StringableNamedEntityInterface;
use Neimheadh\SolidBundle\Doctrine\Entity\Generic\StringableNamedEntityTrait;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Account transaction.
 */
#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\EntityListeners([TransactionEntityListener::class])]
#[ORM\Table(name: 'app_account_transaction')]
#[Sonata\Admin(
    group: 'hidden',
    formFields: [
        'account' => new Sonata\FormField(),
        'thirdParty' => new Sonata\FormField(
            type: ModelAutocompleteType::class,
            options: ['property' => 'name', 'required' => false],
        ),
        'budget' => new Sonata\FormField(
            type: ModelAutocompleteType::class,
            options: ['property' => 'name', 'required' => false]
        ),
        'transactionDate' => new Sonata\FormField(
            type: DatePickerType::class
        ),
        'processed' => new Sonata\FormField(
            type: CheckboxType::class,
            options: ['required' => false]
        ),
        'name' => new Sonata\FormField(),
        'balance' => new Sonata\FormField(),
        'currency' => new Sonata\FormField(),
    ],
    listFields: [
        'transactionDate' => new Sonata\ListField(),
        'processDate' => new Sonata\ListField(),
        'thirdParty' => new Sonata\ListField(),
        'budget' => new Sonata\ListField(),
        'name' => new Sonata\ListField(),
        'account' => new Sonata\ListField(),
        'balance' => new Sonata\ListField(
            type: 'balance',
            fieldDescriptionOptions: [
                'currency_field' => 'currency',
            ]
        ),
        'processed' => new Sonata\ListField(
            type: FieldDescriptionInterface::TYPE_BOOLEAN,
            fieldDescriptionOptions: [
                'editable' => true,
            ]
        ),
    ],
)]
#[Sonata\DatagridValues([
    DatagridInterface::SORT_BY => 'transactionDate',
    DatagridInterface::SORT_ORDER => 'DESC',
])]
class Transaction implements EntityInterface,
                             StringableNamedEntityInterface,
                             AccountManyToOneInterface,
                             BalancedEntityInterface,
                             ThirdPartyManyToOneInterface,
                             BudgetManyToOneInterface
{

    use EntityTrait;
    use StringableNamedEntityTrait;
    use AccountManyToOneTrait;
    use BalancedEntityTrait;
    use ThirdPartyManyToOneTrait;
    use BudgetManyToOneTrait;

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
     * Transaction procession date.
     *
     * Write date is used to differentiate processed and unprocessed
     * transaction, whatever the date set is before or after the current date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(
        name: 'process_date',
        type: 'datetime',
        nullable: true
    )]
    private ?DateTimeInterface $processDate = null;

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
     * Get procession date.
     *
     * @return DateTimeInterface|null
     */
    public function getProcessDate(): ?DateTimeInterface
    {
        return $this->processDate;
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
     * Get if transaction is processed.
     *
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processDate !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function setAccount(?Account $account): self
    {
        if ($this->account !== $account) {
            $this->account?->removeTransaction($this);
            $this->account = $account;
            $this->account?->addTransaction($this);
        }

        return $this;
    }

    /**
     * Set due date.
     *
     * @param DateTimeInterface|null $processDate Due date.
     *
     * @return $this
     */
    public function setProcessDate(?DateTimeInterface $processDate): self
    {
        $this->processDate = $processDate;

        return $this;
    }

    /**
     * Set transaction processed status.
     *
     * Set the procession date to current date if it was not set before.
     *
     * @param bool $processed Processed status.
     *
     * @return $this
     */
    public function setProcessed(bool $processed): self
    {
        if ($processed && $this->processDate === null) {
            $this->setProcessDate(new DateTime());
        }

        if (!$processed && $this->processDate !== null) {
            $this->setProcessDate(null);
        }

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