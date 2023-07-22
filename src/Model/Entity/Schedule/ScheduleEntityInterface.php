<?php

namespace App\Model\Entity\Schedule;

use App\Schedule\Configuration\ScheduleConfigurationInterface;
use DateInterval;
use DateTime;
use DateTimeInterface;

/**
 * Schedule entity.
 */
interface ScheduleEntityInterface extends ScheduleConfigurationInterface
{

    /**
     * Get generator class.
     *
     * @return string|null
     */
    public function getGeneratorClass(): ?string;

    /**
     * Set schedule finish date.
     *
     * @param DateTimeInterface|null $date Schedule finish date.
     *
     * @return $this
     */
    public function setFinishAt(?DateTimeInterface $date): self;

    /**
     * Set schedule execution interval.
     *
     * @param DateInterval|null $interval Schedule execution interval.
     *
     * @return $this
     */
    public function setInterval(?DateInterval $interval): self;

    /**
     * Set schedule start date.
     *
     * @param DateTimeInterface|null $date Schedule start date.
     *
     * @return $this
     */
    public function setStartAt(?DateTimeInterface $date): self;

}