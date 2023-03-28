<?php

namespace App\Import\Parser;

/**
 * Sum the given values.
 *
 * @extends AbstractParser<mixed>
 */
final class SumValueParser extends AbstractParser
{

    /**
     * Value parsers.
     *
     * @var array|ParserInterface[]
     */
    public readonly array $parsers;

    /**
     * @param ParserInterface ...$parsers Parsers.
     */
    public function __construct(
        ParserInterface ...$parsers
    ) {
        $this->parsers = $parsers;
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(mixed $value): bool
    {
        return is_array($value);
    }

    /**
     * {@inheritDoc}
     */
    public function parseValue(mixed $value): mixed
    {
        $sum = null;

        foreach ($value as $i => $entry) {
            if (isset($this->parsers[$i])) {
                $entry = $this->parsers[$i]->parse($entry);
            }

            if ($sum === null) {
                $sum = $entry;
            } elseif ($entry) {
                $sum += $entry;
            }
        }

        return $sum;
    }

}