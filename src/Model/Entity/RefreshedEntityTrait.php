<?php

namespace App\Model\Entity;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Refreshed entity trait.
 */
trait RefreshedEntityTrait
{

    /**
     * Last refresh date.
     *
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $refreshedAt;

    public function __construct()
    {
        $this->refreshedAt = new DateTime();
    }

    /**
     * {@inheritDoc}
     */
    public function getRefreshedAt(): DateTimeInterface
    {
        return $this->refreshedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function needRefresh(): bool
    {
        $now = new DateTime();

        // Not refreshed if not in the start-end period.
        if (($this->endAt !== null && $now > $this->endAt)
          || $now < $this->startAt
          || $this->periodicity === null
        ) {
            return false;
        }

        $next = clone($this->refreshedAt);
        $next->add(
          DateInterval::createFromDateString($this->periodicity)
        );

        return $now > $next;
    }

    /**
     * {@inheritDoc}
     */
    public function setRefreshedAt(DateTimeInterface $date): self
    {
        $this->refreshedAt = $date;

        return $this;
    }

}