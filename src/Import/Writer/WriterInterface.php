<?php

namespace App\Import\Writer;

use App\Import\Configuration\ConfigurationInterface;

/**
 * Import writer.
 *
 * @template T
 */
interface WriterInterface
{

    /**
     * Check given input is supported by the writer.
     *
     * @param mixed $input   Writer input.
     * @param array $options Writer options.
     *
     * @return bool
     */
    public function isSupported(mixed $input, array $options = []): bool;

    /**
     * Write given input.
     *
     * @param mixed $input   Writer input.
     * @param array $options Writer options.
     *
     * @return T
     */
    public function write(mixed $input, array $options = []): mixed;

}