<?php

namespace App\Repository\Account;

use App\Entity\Account\Bank;
use App\Entity\Account\Transaction;
use App\Model\Repository\Generic\NamedEntityRepositoryInterface;
use App\Model\Repository\Generic\NamedEntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Account bank repository.
 */
class BankRepository extends ServiceEntityRepository implements
    NamedEntityRepositoryInterface
{

    use NamedEntityRepositoryTrait;

    /**
     * @param ManagerRegistry $registry Doctrine registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bank::class);
    }

}