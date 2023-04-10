<?php

namespace App\Repository\ThirdParty;

use App\Entity\ThirdParty\ThirdParty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Account third party repository.
 */
class ThirdPartyRepository extends ServiceEntityRepository
{

    /**
     * @param ManagerRegistry $registry Doctrine repository.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThirdParty::class);
    }

}