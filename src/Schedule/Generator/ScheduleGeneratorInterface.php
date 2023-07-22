<?php

namespace App\Schedule\Generator;

use App\Schedule\Configuration\ScheduleConfigurationInterface;

/**
 * Schedule generator.
 */
interface ScheduleGeneratorInterface
{

    /**
     * Generate the schedule item.
     *
     * @param ScheduleConfigurationInterface $configuration Schedule
     *                                                      configuration.
     *
     * @return object
     */
    public function generate(
        ScheduleConfigurationInterface $configuration
    ): object;

    /**
     * Get if the schedule item must be re-generated.
     *
     * @param ScheduleConfigurationInterface $configuration Schedule
     *                                                      configuration.
     *
     * @return bool
     */
    public function isScheduled(
        ScheduleConfigurationInterface $configuration
    ): bool;

    /**
     * Get if given configuration is supported.
     *
     * @param ScheduleConfigurationInterface $configuration Schedule
     *                                                      configuration.
     *
     * @return bool
     */
    public function isSupported(
        ScheduleConfigurationInterface $configuration
    ): bool;

}