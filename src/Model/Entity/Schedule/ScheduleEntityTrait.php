<?php

namespace App\Model\Entity\Schedule;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * Schedule entity trait.
 */
trait ScheduleEntityTrait
{

    /**
     * Schedule finish date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(
        name: 'finish_at',
        type: 'datetime',
        nullable: true,
    )]
    private ?DateTimeInterface $finishAt = null;

    /**
     * Schedule last execution date.
     *
     * @var int|null
     */
    #[ORM\Column(
        name: 'last_execution',
        type: 'integer',
        nullable: true,
    )]
    private ?int $lastExecution = null;

    /**
     * Schedule execution interval.
     *
     * @var string|null
     */
    #[ORM\Column(
        type: 'integer',
        length: 16,
        options: ['default' => 0, 'unsigned' => true],
    )]
    private ?int $interval = null;

    /**
     * Schedule start date.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(
        name: 'start_at',
        type: 'datetime',
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private ?DateTimeInterface $startAt = null;

    public function __construct()
    {
        $this->startAt = new DateTime();
    }

    /**
     * {@inheritDoc}
     */
    public function getFinishAt(): ?DateTimeInterface
    {
        return $this->finishAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getInterval(): ?DateInterval
    {
        $y = floor($this->interval);

        try {
            $interval = new DateInterval('PT' . $this->interval . 'S');
            dd($interval);
        } catch (Exception) {
        }

        return $interval ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getLastExecution(): ?DateTime
    {
        if ($this->lastExecution === null) {
            return null;
        }

        $lastExecution = new DateTime();
        $lastExecution->setTimestamp($this->lastExecution);

        return $lastExecution;
    }

    /**
     * {@inheritDoc}
     */
    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setFinishAt(?DateTimeInterface $date): self
    {
        $this->finishAt = $date;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setInterval(?DateInterval $interval): self
    {
        if ($interval === null) {
            $this->interval = null;

            return $this;
        }

        $this->interval = $interval->s
            + $interval->i * 60
            + $interval->h * 60 * 60
            + $interval->d * 60 * 60 * 24
            + $interval->m * 60 * 60 * 24 * 30
            + $interval->y * 60 * 60 * 24 * 365;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setLastExecution(?DateTime $lastExecution): self
    {
        $this->lastExecution = $lastExecution?->getTimestamp();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setStartAt(?DateTimeInterface $date): self
    {
        $this->startAt = $date;

        return $this;
    }

}