<?php

namespace App\Import\Monitoring;

/**
 * Progress monitoring.
 */
interface ProgressInterface
{

    /**
     * Advance progress x steps.
     *
     * @param int $step Number of steps to advance progress.
     *
     * @return void
     */
    public function advance(int $step = 1): void;

    /**
     * Finished progress.
     *
     * @return void
     */
    public function finish(): void;

    /**
     * Start progress.
     *
     * @param int|null $max     Progress max value.
     * @param int|null $startAt Progress start.
     *
     * @return void
     */
    public function start(int $max = null, int $startAt = null): void;

}