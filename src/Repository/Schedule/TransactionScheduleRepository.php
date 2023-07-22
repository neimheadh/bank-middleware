<?php

namespace App\Repository\Schedule;

use App\Entity\Schedule\TransactionSchedule;
use App\Model\Repository\Schedule\ScheduleEntityRepositoryInterface;
use App\Model\Repository\Schedule\ScheduleEntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Transaction schedule repository.
 *
 * @implements ScheduleEntityRepositoryInterface<TransactionSchedule>
 */
class TransactionScheduleRepository extends ServiceEntityRepository implements
    ScheduleEntityRepositoryInterface
{

    use ScheduleEntityRepositoryTrait;

    /**
     * @param ManagerRegistry $registry Doctrine registry.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionSchedule::class);
    }
}