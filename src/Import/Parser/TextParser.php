<?php

namespace App\Import\Parser;

/**
 * Text value parser.
 *
 * @extends AbstractParser<string>
 */
final class TextParser extends AbstractParser
{

    /**
     * @param bool $trim Trim the given value.
     */
    public function __construct(
        private readonly bool $trim = true,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    protected function parseValue(mixed $value): string
    {
        return $this->trim ? trim($value) : (string) $value;
    }

}