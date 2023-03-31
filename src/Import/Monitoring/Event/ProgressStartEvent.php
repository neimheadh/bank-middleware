<?php

namespace App\Import\Monitoring\Event;

/**
 * Progress start event.
 */
class ProgressStartEvent
{

    /**
     * @param int|null $max     Progress max value.
     * @param int|null $startAt Progress start.
     */
    public function __construct(
        public readonly ?int $max = null,
        public readonly ?int $startAt = null
    ) {
    }

}