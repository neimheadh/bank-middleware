<?php

namespace App\Import\Monitoring;

use App\Import\Monitoring\Event\ProgressAdvanceEvent;
use App\Import\Monitoring\Event\ProgressFinishEvent;
use App\Import\Monitoring\Event\ProgressStartEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Event dispatch progress monitor.
 */
class EventProgress implements ProgressInterface
{

    /**
     * @param EventDispatcherInterface $dispatcher Event dispatcher.
     */
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function advance(int $step = 1): void
    {
        $this->dispatcher->dispatch(new ProgressAdvanceEvent($step));
    }

    /**
     * {@inheritDoc}
     */
    public function finish(): void
    {
        $this->dispatcher->dispatch(new ProgressFinishEvent());
    }

    /**
     * {@inheritDoc}
     */
    public function start(int $max = null, int $startAt = null): void
    {
        $this->dispatcher->dispatch(new ProgressStartEvent($max, $startAt));
    }

}