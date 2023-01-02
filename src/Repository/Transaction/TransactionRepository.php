<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\Transaction;
use App\Model\Repository\UidEntityRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Transaction entity repository.
 */
class TransactionRepository extends ServiceEntityRepository implements
  UidEntityRepositoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

}