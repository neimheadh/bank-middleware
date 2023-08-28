<?php

namespace App\Model\Entity\Schedule;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * Schedule entity trait.
 *
 * @todo See if it's possible to record the date interval in several fields on
 *       SGBD driver side.
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
     * Schedule execution interval day.
     *
     * @var int
     */
    #[ORM\Column(
        name: 'interval_day',
        type: 'smallint',
        options: ['default' => 0, 'unsigned' => true],
    )]
    private int $intervalDay = 0;

    /**
     * Schedule execution interval hours.
     *
     * @var int
     */
    #[ORM\Column(
        name: 'interval_hour',
        type: 'smallint',
        options: ['default' => 0, 'unsigned' => true],
    )]
    private int $intervalHour = 0;

    /**
     * Schedule execution interval minutes.
     *
     * @var int
     */
    #[ORM\Column(
        name: 'interval_minute',
        type: 'smallint',
        options: ['default' => 0, 'unsigned' => true],
    )]
    private int $intervalMinute = 0;

    /**
     * Schedule execution interval months.
     *
     * @var int
     */
    #[ORM\Column(
        name: 'interval_month',
        type: 'smallint',
        options: ['default' => 0, 'unsigned' => true],
    )]
    private int $intervalMonth = 0;

    /**
     * Schedule execution interval seconds.
     *
     * @var int
     */
    #[ORM\Column(
        name: 'interval_second',
        type: 'smallint',
        options: ['default' => 0, 'unsigned' => true],
    )]
    private int $intervalSecond = 0;

    /**
     * Schedule execution interval years.
     *
     * @var int
     */
    #[ORM\Column(
        name: 'interval_year',
        type: 'smallint',
        options: ['default' => 0, 'unsigned' => true],
    )]
    private int $intervalYear = 0;

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
        try {
            return new DateInterval(
                sprintf(
                    'P%sY%sM%sDT%sH%sM%sS',
                    $this->intervalYear,
                    $this->intervalMonth,
                    $this->intervalDay,
                    $this->intervalHour,
                    $this->intervalMinute,
                    $this->intervalSecond,
                )
            );
        } catch (Exception) {
        }

        return null;
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
    public function setInterval(DateInterval $interval): self
    {
        $this->intervalYear = $interval->y;
        $this->intervalMonth = $interval->m;
        $this->intervalDay = $interval->d;
        $this->intervalHour = $interval->h;
        $this->intervalMinute = $interval->i;
        $this->intervalSecond = $interval->s;

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