<?php

namespace App\Import\Processor;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Result\ResultInterface;

/**
 * Import processor.
 */
interface ProcessorInterface
{

    /**
     * Check given input is supported by the processor.
     *
     * @param mixed                       $input  Processor input.
     * @param ConfigurationInterface|null $config Processing configuration.
     *
     * @return bool
     */
    public function isSupported(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): bool;

    /**
     * Process given input.
     *
     * @param mixed                       $input  Processor input.
     * @param ConfigurationInterface|null $config Processing configuration.
     *
     * @return ResultInterface
     */
    public function process(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): ResultInterface;
}