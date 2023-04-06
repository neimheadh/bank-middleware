<?php

namespace App\Repository\Account;

use App\Entity\Account\Account;
use App\Entity\Account\Transaction;
use App\Model\Repository\Currency\BalancedEntityRepositoryInterface;
use App\Model\Repository\Currency\BalancedEntityRepositoryTrait;
use App\Model\Repository\Generic\NamedEntityRepositoryInterface;
use App\Model\Repository\Generic\NamedEntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Account transactions repository.
 */
class TransactionRepository extends ServiceEntityRepository implements
    BalancedEntityRepositoryInterface,
    NamedEntityRepositoryInterface
{

    use BalancedEntityRepositoryTrait;
    use NamedEntityRepositoryTrait;

    /**
     * @param ManagerRegistry $registry Doctrine registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * Get non-processed transactions for the given account.
     *
     * @param Account $account Transactions account.
     *
     * @return iterable<Transaction>
     */
    public function findRemainingTransactionsForAccount(
       Account $account
    ): iterable {
        return $this->createQueryBuilder('t')
            ->join('t.account', 'a')
            ->where('t.account = :account')
            ->andWhere('t.processDate IS NULL')
            ->getQuery()
            ->setParameters([
                'account' => $account,
            ])->toIterable();
    }

}