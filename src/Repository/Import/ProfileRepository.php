<?php

namespace App\Repository\Import;

use App\Entity\Import\Profile;
use App\Model\Repository\Generic\CodeEntityRepositoryInterface;
use App\Model\Repository\Generic\CodeEntityRepositoryTrait;
use App\Model\Repository\Generic\NamedEntityRepositoryInterface;
use App\Model\Repository\Generic\NamedEntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Import profile repository.
 */
class ProfileRepository extends ServiceEntityRepository implements
    NamedEntityRepositoryInterface,
    CodeEntityRepositoryInterface
{

    use NamedEntityRepositoryTrait;
    use CodeEntityRepositoryTrait;

    /**
     * @param ManagerRegistry $registry Doctrine registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

}