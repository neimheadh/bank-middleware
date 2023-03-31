<?php

namespace App\Import\Reader;

use App\Import\Configuration\ConfigurationInterface;

/**
 * Import reader.
 *
 * @template T
 */
interface ReaderInterface
{

    /**
     * Check given input is supported by the reader.
     *
     * @param mixed $input   Reader input.
     * @param array $options Reading options.
     *
     * @return bool
     */
    public function isSupported(mixed $input, array $options = []): bool;

    /**
     * Read given input.
     *
     * @param mixed $input   Reader input.
     * @param array $options Reading options.
     *
     * @return T
     */
    public function read(
        mixed $input,
        array $options = [],
    ): mixed;

}