<?php

namespace App\Repository\User;

use App\Entity\User\AccessList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * User access list repository.
 */
class AccessListRepository extends ServiceEntityRepository
{

    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessList::class);
    }

}