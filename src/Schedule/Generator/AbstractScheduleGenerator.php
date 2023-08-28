<?php

namespace App\Schedule\Generator;

use App\Schedule\Configuration\ScheduleConfigurationInterface;
use DateTime;
use InvalidArgumentException;

/**
 * Schedule generator.
 */
abstract class AbstractScheduleGenerator implements
    ScheduleGeneratorInterface
{

    /**
     * {@inheritDoc}
     */
    public function generate(
        ScheduleConfigurationInterface $configuration
    ): object {
        if (!$this->isSupported($configuration)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Schedule configuration of type %s is not supported '
                    . 'by generator %s.',
                    get_class($configuration),
                    self::class
                )
            );
        }

        $object = $this->getObject($configuration);
        $configuration->setLastExecution(new DateTime());

        return $object;

    }

    /**
     * {@inheritDoc}
     */
    public function isScheduled(
        ScheduleConfigurationInterface $configuration
    ): bool {
        $now = new DateTime();
        $last = $configuration->getLastExecution();
        $start = $configuration->getStartAt();
        $end = $configuration->getFinishAt();
        $interval = $configuration->getInterval();

        return $start <= $now
            && ($end === null || $end >= $now)
            && ($last === null || $last->add($interval) <= $now);
    }

    /**
     * Get the generated object.
     *
     * @param ScheduleConfigurationInterface $configuration Schedule
     *                                                      configuration.
     *
     * @return object
     */
    abstract protected function getObject(
        ScheduleConfigurationInterface $configuration
    ): object;

}