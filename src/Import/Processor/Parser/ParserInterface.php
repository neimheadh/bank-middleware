<?php

namespace App\Import\Processor\Parser;

/**
 * Import value parser.
 *
 * @template T
 */
interface ParserInterface
{

    /**
     * Check the given value is supported by the parser.
     *
     * @param mixed $value Input value.
     *
     * @return bool
     */
    public function isSupported(mixed $value): bool;

    /**
     * Parse given value.
     *
     * @param mixed $value Input value.
     *
     * @return T
     */
    public function parse(mixed $value): mixed;
}