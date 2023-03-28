<?php

namespace App\Import\Writer;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Result\ResultInterface;

/**
 * Import writer.
 */
interface WriterInterface
{

    /**
     * Check given input is supported by the writer.
     *
     * @param mixed                       $input  Writer input.
     * @param ConfigurationInterface|null $config Writing configuration.
     *
     * @return bool
     */
    public function isSupported(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): bool;

    /**
     * Write given input.
     *
     * @param mixed                       $input  Writer input.
     * @param ConfigurationInterface|null $config Writer configuration.
     *
     * @return ResultInterface
     */
    public function write(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface;

}