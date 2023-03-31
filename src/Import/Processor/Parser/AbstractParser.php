<?php

namespace App\Import\Processor\Parser;

use App\Import\Exception\Processor\ParserInputNotSupportedException;
use Throwable;

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
     *
     * @throws Throwable
     */
    public function parse(mixed $value): mixed
    {
        if (!$this->isSupported($value)) {
            throw $this->getUnsupportedException($value);
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

    /**
     * Get input not supported exception.
     *
     * @param mixed $value Value given to the parser.
     *
     * @return Throwable
     */
    protected function getUnsupportedException(mixed $value): Throwable
    {
        return new ParserInputNotSupportedException(
            $this::class,
            $value
        );
    }

}