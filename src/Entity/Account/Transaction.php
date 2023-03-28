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
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Account transaction.
 */
#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: 'app_account_transaction')]
#[Sonata\Admin]
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

}