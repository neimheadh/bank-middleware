<?php

namespace App\Model\Repository\Schedule;

use App\Model\Entity\Schedule\ScheduleEntityInterface;

/**
 * Schedule entity repository interface.
 *
 * @template T as ScheduleEntityInterface
 */
interface ScheduleEntityRepositoryInterface
{

    /**
     * Find scheduled entities.
     *
     * Returns list of scheduled entities that must be executed.
     *
     * @return array<T>
     */
    public function findScheduled(): array;
}