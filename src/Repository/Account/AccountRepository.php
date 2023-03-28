<?php

namespace App\Repository\Account;

use App\Entity\Account\Account;
use App\Model\Repository\Currency\BalancedEntityRepositoryTrait;
use App\Model\Repository\Generic\CodeEntityRepositoryInterface;
use App\Model\Repository\Generic\CodeEntityRepositoryTrait;
use App\Model\Repository\Generic\NamedEntityRepositoryInterface;
use App\Model\Repository\Generic\NamedEntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Bank account repository.
 *
 * @implements NamedEntityRepositoryInterface<Account>
 */
class AccountRepository extends ServiceEntityRepository implements
    NamedEntityRepositoryInterface,
    CodeEntityRepositoryInterface
{
    use BalancedEntityRepositoryTrait;
    use NamedEntityRepositoryTrait;
    use CodeEntityRepositoryTrait;

    /**
     * @param ManagerRegistry $registry Doctrine registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

}