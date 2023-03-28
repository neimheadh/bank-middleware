<?php

namespace App\Import\Parser;

/**
 * Parser given a fixed value whatever it is given as input.
 *
 * @extends AbstractParser<mixed>
 */
final class FixedValueParser extends AbstractParser
{

    /**
     * @param mixed $value Delivered value.
     */
    public function __construct(
        public readonly mixed $value
    ) {
    }

    /**
     * {@inheritDoc}
     */
    protected function parseValue(mixed $value): mixed
    {
        return $this->value;
    }

}