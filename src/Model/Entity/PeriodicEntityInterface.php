<?php

namespace App\Model\Entity;

/**
 * Entity with periodicity.
 */
interface PeriodicEntityInterface
{

    /**
     * Unknown periodicity.
     */
    public const UNKNOWN = 0;

    /**
     * Hourly periodicity.
     */
    public const HOURLY = 1;

    /**
     * Daily periodicity.
     */
    public const DAILY = 2;

    /**
     * Weekly periodicity.
     */
    public const WEEKLY = 3;

    /**
     * Yearly periodicity.
     */
    public const MONTHLY = 4;

    /**
     * Yearly periodicity.
     */
    public const YEARLY = 5;

    /**
     * Periodicity list.
     */
    public const PERIODICITY = [
      'unknown' => self::UNKNOWN,
      'hourly' => self::HOURLY,
      'daily' => self::DAILY,
      'weekly' => self::WEEKLY,
      'monthly' => self::MONTHLY,
      'yearly' => self::YEARLY,
    ];

    /**
     * Get periodicity.
     *
     * @return int
     */
    public function getPeriodicity(): int;

    /**
     * Set periodicity.
     *
     * @param int $periodicity Entity periodicity.
     *
     * @return $this
     */
    public function setPeriodicity(int $periodicity): self;

}