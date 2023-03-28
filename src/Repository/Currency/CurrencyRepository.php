<?php

namespace App\Repository\Currency;

use App\Entity\Currency\Currency;
use App\Model\Repository\Generic\CodeEntityRepositoryInterface;
use App\Model\Repository\Generic\CodeEntityRepositoryTrait;
use App\Model\Repository\Generic\DefaultEntityRepositoryInterface;
use App\Model\Repository\Generic\DefaultEntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Currency repository.
 *
 * @implements CodeEntityRepositoryInterface<Currency>
 * @implements DefaultEntityRepositoryInterface<Currency>
 */
class CurrencyRepository extends ServiceEntityRepository implements
    CodeEntityRepositoryInterface,
    DefaultEntityRepositoryInterface
{

    use CodeEntityRepositoryTrait;
    use DefaultEntityRepositoryTrait;

    /**
     * @param ManagerRegistry $registry Doctrine registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

}