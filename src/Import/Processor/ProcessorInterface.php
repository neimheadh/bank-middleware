<?php

namespace App\Import\Processor;

/**
 * Import processor.
 *
 * @template T
 */
interface ProcessorInterface
{

    /**
     * Check given input is supported by the processor.
     *
     * @param mixed $input   Processor input.
     * @param array $options Processor options.
     *
     * @return bool
     */
    public function isSupported(
        mixed $input,
        array $options = [],
    ): bool;

    /**
     * Process given input.
     *
     * @param mixed $input   Processor input.
     * @param array $options Processor options.
     *
     * @return T
     */
    public function process(
        mixed $input,
        array $options = [],
    ): mixed;

}