<?php

namespace App\Import\Processor\Parser;

use App\Import\Exception\Processor\ParseValueException;
use Stringable;

/**
 * Float value parser.
 *
 * @extends AbstractParser<float>
 */
final class FloatParser extends AbstractParser
{

    /**
     * Use the round() function to round float value.
     */
    public const ROUND_ROUND = 0;

    /**
     * Use the ceil() function to round float value.
     */
    public const ROUND_CEIL = 1;

    /**
     * Use the float() function to round float value.
     */
    public const ROUND_FLOOR = 2;

    /**
     * Decimal separator chars.
     *
     * @var array|string[]
     */
    public readonly array $decimalSeparators;

    /**
     * Thousands separator chars.
     *
     * @var array|string[]
     */
    public readonly array $thousandSeparators;

    /**
     * Digit after zero round count.
     *
     * @var int|null
     */
    public readonly ?int $round;

    /**
     * Round method.
     *
     * @var int
     */
    public readonly int $roundMethod;

    /**
     * @param array|string $decimalSeparators  Decimal separator chars.
     * @param array|string $thousandSeparators Thousands separator chars
     * @param int|null     $round              Digit after zero round count.
     * @param int|null     $ceil               Digit after zero ceil count.
     * @param int|null     $floor              Digit after zero floor count.
     * @param bool         $noError            Send 0 on error instead throwing
     *                                         an exception.
     */
    public function __construct(
        array|string $decimalSeparators = [','],
        array|string $thousandSeparators = ["'"],
        int|null $round = null,
        int|null $ceil = null,
        int|null $floor = null,
        private readonly bool $noError = false,
    ) {
        $this->decimalSeparators = is_array($decimalSeparators)
            ? $decimalSeparators
            : [$decimalSeparators];
        $this->thousandSeparators = is_array($thousandSeparators)
            ? $thousandSeparators
            : [$thousandSeparators];

        $roundMethod = self::ROUND_ROUND;
        if ($ceil !== null) {
            $roundMethod = self::ROUND_CEIL;
            $round = $ceil;
        }

        if ($floor !== null) {
            $roundMethod = self::ROUND_FLOOR;
            $round = $floor;
        }

        $this->round = $round;
        $this->roundMethod = $roundMethod;
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(mixed $value): bool
    {
        return $this->noError
            || is_numeric($value)
            || is_string($value)
            || $value instanceof Stringable;
    }

    /**
     * {@inheritDoc}
     */
    protected function parseValue(mixed $value): float
    {
        if ($this->noError
            && !is_string($value)
            && !$value instanceof Stringable
        ) {
            return 0.0;
        }

        $number = trim((string)$value);

        // Replace thousand separators by empty char.
        $number = str_replace(
            $this->thousandSeparators,
            array_fill(0, count($this->thousandSeparators), ''),
            $number
        );

        // Replace decimal separators by a dot char.
        $number = str_replace(
            $this->decimalSeparators,
            array_fill(0, count($this->decimalSeparators), '.'),
            $number
        );

        // Remove spaces.
        $number = str_replace(' ', '', $number);

        if (!is_numeric($number)) {
            if ($this->noError) {
                return 0.0;
            }

            throw new ParseValueException(
                sprintf(
                    'Unable to transform "%s" into float value.',
                    $value
                )
            );
        }

        $number = floatval($number);

        return $this->roundValue($number);
    }

    /**
     * Round the parsed value.
     *
     * @param float $value Parsed value.
     *
     * @return float
     */
    private function roundValue(float $value): float
    {
        if ($this->round !== null) {
            $r = pow(10, $this->round);
            return match ($this->roundMethod) {
                self::ROUND_CEIL =>
                    ceil($value * $r) / $r,
                self::ROUND_FLOOR =>
                    floor($value * $r) / $r,
                default =>
                    round($value * $r) / $r,
            };
        }

        return $value;
    }

}