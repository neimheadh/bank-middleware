<?php

namespace App\Import\Monitoring\Event;

/**
 * Progress advance event.
 */
class ProgressAdvanceEvent
{

    /**
     * @param int $step Number of steps to advance progress.
     */
    public function __construct(public readonly int $step = 1)
    {
    }

}