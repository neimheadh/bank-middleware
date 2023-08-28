<?php

namespace App\Model\Repository\Schedule;

use App\Model\Entity\Schedule\ScheduleEntityInterface;
use DateTime;
use Doctrine\ORM\QueryBuilder;

/**
 * Schedule entity repository trait.
 */
trait ScheduleEntityRepositoryTrait
{

    /**
     * {@inheritDoc}
     */
    public function findScheduled(?DateTime $now = null): array
    {
        $now = $now ?: new DateTime();

        return array_filter(
            $this->buildScheduledQuery($now)
                ->getQuery()
                ->execute(),
            fn(ScheduleEntityInterface $entity) => $entity->getLastExecution(
                ) === null
                || $entity->getLastExecution()->add(
                    $entity->getInterval()
                ) < $now,
        );
    }

    /**
     * Build find scheduled entities query.
     *
     * @return QueryBuilder
     */
    protected function buildScheduledQuery(DateTime $now): QueryBuilder
    {
        $qb = $this->createQueryBuilder('e');

        return $qb->where('e.startAt <= :now')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('e.finishAt'),
                    $qb->expr()->gte('e.finishAt', ':now'),
                ),
            )
            ->setParameter('now', $now);
    }

}