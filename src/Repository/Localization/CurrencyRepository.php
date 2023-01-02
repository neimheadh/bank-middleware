<?php

namespace App\Repository\Localization;

use App\Entity\Localization\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Currency entity repository.
 */
class CurrencyRepository extends ServiceEntityRepository
{

    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

}