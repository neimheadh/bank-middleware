<?php

namespace App\Schedule\Configuration;

use DateInterval;
use DateTime;
use DateTimeInterface;

/**
 * Schedule item.
 */
interface ScheduleConfigurationInterface
{
    /**
     * Get schedule finish date.
     *
     * @return DateTimeInterface|null
     */
    public function getFinishAt(): ?DateTimeInterface;

    /**
     * Get schedule last execution date.
     *
     * @return DateTime|null
     */
    public function getLastExecution(): ?DateTime;

    /**
     * Get schedule execution interval.
     *
     * @return DateInterval|null
     */
    public function getInterval(): ?DateInterval;

    /**
     * Get schedule start date.
     *
     * @return DateTimeInterface|null
     */
    public function getStartAt(): ?DateTimeInterface;

    /**
     * Set schedule last execution date.
     *
     * @param DateTime|null $date Last execution date.
     *
     * @return $this
     */
    public function setLastExecution(?DateTime $date): self;
}