<?php

namespace App\Model\Repository\Schedule;

use DateTime;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Schedule entity repository trait.
 */
trait ScheduleEntityRepositoryTrait
{

    /**
     * {@inheritDoc}
     */
    public function findScheduled(): array
    {
        return $this->buildScheduledQuery()
            ->getQuery()
            ->execute();
    }

    /**
     * Build find scheduled entities query.
     *
     * @return QueryBuilder
     */
    protected function buildScheduledQuery(): QueryBuilder
    {
        $now = new DateTime();
        $qb = $this->createQueryBuilder('e');

        return $qb->where('e.startAt >= :now')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('e.finishAt'),
                    $qb->expr()->lte('e.finishAt', ':now'),
                ),
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('e.lastExecution'),
                    $qb->expr()->gte(
                        $qb->expr()->sum('e.lastExecution', 'e.interval'),
                        ':now'
                    )
                )
            )
            ->setParameter('now', $now);
    }
}