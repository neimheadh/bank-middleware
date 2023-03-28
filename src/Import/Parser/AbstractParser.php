<?php

namespace App\Import\Parser;

use App\Import\Exception\ParserInputNotSupportedException;

/**
 * Import parser.
 *
 * @template T
 * @implements ParserInterface<T>
 */
abstract class AbstractParser implements ParserInterface
{

    /**
     * {@inheritDoc}
     */
    public function isSupported(mixed $value): bool
    {
        // By default all values are supported.
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function parse(mixed $value): mixed
    {
        if ($value instanceof FixedValueParser) {
            $value = $value->parse(null);
        }

        if (!$this->isSupported($value)) {
            throw new ParserInputNotSupportedException(
                $this::class,
                $value
            );
        }

        return $this->parseValue($value);
    }

    /**
     * Parse given value.
     *
     * @param mixed $value Input value.
     *
     * @return T
     */
    abstract protected function parseValue(mixed $value): mixed;
}