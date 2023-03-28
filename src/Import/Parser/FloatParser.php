<?php

namespace App\Import\Parser;

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
    public readonly array $decimalSeparator;

    /**
     * Thousands separator chars.
     *
     * @var array|string[]
     */
    public readonly array $thousandSeparator;

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
     * @param array|string  $decimalSeparator  Decimal separator chars.
     * @param array|string  $thousandSeparator Thousands separator chars.
     * @param array|float[] $operators         Sign operators.
     * @param int|null      $round             Digit after zero round count.
     * @param int|null      $ceil              Digit after zero ceil count.
     * @param int|null      $floor             Digit after zero floor count.
     */
    public function __construct(
        array|string $decimalSeparator = [','],
        array|string $thousandSeparator = [' ', "'"],
        public readonly array $operators = ['+' => 1.0, '-' => -1.0],
        int|null $round = null,
        int|null $ceil = null,
        int|null $floor = null,
    ) {
        $this->decimalSeparator = is_array($decimalSeparator)
            ? $decimalSeparator
            : [$decimalSeparator];
        $this->thousandSeparator = is_array($thousandSeparator)
            ? $thousandSeparator
            : [$thousandSeparator];

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
        if (is_numeric($value) || is_null($value)) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        $chars = str_split($value);
        foreach ($chars as $char) {
            if (!is_numeric($char)
                && !in_array($char, $this->decimalSeparator)
                && !in_array($char, $this->thousandSeparator)
                && !in_array($char, array_keys($this->operators))
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function parseValue(mixed $value): float
    {
        if (is_null($value)) {
            return 0.0;
        }

        foreach ($this->decimalSeparator as $separator) {
            $value = str_replace($separator, '.', $value);
        }

        foreach ($this->thousandSeparator as $separator) {
            $value = str_replace($separator, '', $value);
        }

        $mul = 1.0;
        $chars = str_split($value);
        foreach ($chars as $i => $char) {
            if (in_array($char, $this->decimalSeparator)) {
                $value = sprintf(
                    '%s.%s',
                    substr($value, 0, $i),
                    substr($value, $i + 1)
                );
            } elseif (in_array($char, $this->thousandSeparator)) {
                $value = substr($value, 0, $i)
                    . substr($value, $i + 1);
            } elseif (isset($this->operators[$char])) {
                $value = substr($value, 0, $i)
                    . substr($value, $i + 1);
                $mul *= $this->operators[$char];
            }
        }

        $value = floatval($value) * $mul;

        if ($this->round !== null) {
            $r = pow(10, $this->round);
            $value = match ($this->roundMethod) {
                self::ROUND_CEIL => ceil($value * $r) / $r,
                self::ROUND_FLOOR => floor($value * $r) / $r,
                default => round($value * $r) / $r,
            };
        }

        return $value;
    }

}