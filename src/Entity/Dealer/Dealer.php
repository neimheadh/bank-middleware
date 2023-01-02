<?php

namespace App\Entity\Dealer;

use App\Entity\Transaction\Transaction;
use App\Model\Entity\DatedEntityInterface;
use App\Model\Entity\DatedEntityTrait;
use App\Model\Entity\EntityInterface;
use App\Model\Entity\EntityTrait;
use App\Model\Entity\NamedEntityInterface;
use App\Model\Entity\NamedEntityTrait;
use App\Model\Entity\Transaction\TransactionEntityMapInterface;
use App\Model\Entity\Transaction\TransactionEntityMapTrait;
use App\Repository\Dealer\DealerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Dealer.
 */
#[ORM\Entity(repositoryClass: DealerRepository::class)]
#[ORM\Table(name: 'dealer_dealer')]
class Dealer implements EntityInterface,
                        DatedEntityInterface,
                        NamedEntityInterface,
                        TransactionEntityMapInterface
{

    use EntityTrait;
    use DatedEntityTrait;
    use NamedEntityTrait;
    use TransactionEntityMapTrait;

    /**
     * Dealer transactions.
     *
     * @var Collection<Transaction>
     */
    #[ORM\OneToMany(
      mappedBy: 'dealer',
      targetEntity: Transaction::class,
      cascade: ['persist'],
    )]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

}