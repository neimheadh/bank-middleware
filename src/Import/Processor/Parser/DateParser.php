<?php

namespace App\Import\Processor\Parser;

use DateTime;
use DateTimeInterface;

/**
 * Date import value parser.
 *
 * @extends AbstractParser<DateTimeInterface>
 */
final class DateParser extends AbstractParser
{

    /**
     * @param string $format       Date format.
     * @param int    $hour         Hour for the date.
     * @param int    $minutes      Minutes for the date.
     * @param int    $seconds      Seconds for the date.
     * @param int    $microseconds Microseconds for the date.
     */
    public function __construct(
        public readonly string $format = 'Y-m-d',
        public readonly int $hour = 0,
        public readonly int $minutes = 0,
        public readonly int $seconds = 0,
        public readonly int $microseconds = 0
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(mixed $value): bool
    {
        return $value instanceof DateTimeInterface
            || (is_string($value)
                && DateTime::createFromFormat($this->format, $value) !== false
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function parseValue(mixed $value): DateTimeInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        $value = DateTime::createFromFormat($this->format, $value);
        $value->setTime(
            $this->hour,
            $this->minutes,
            $this->seconds,
            $this->microseconds
        );

        return $value;
    }

}