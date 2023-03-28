<?php

namespace App\Import\Reader;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Result\ResultInterface;

/**
 * Import reader.
 */
interface ReaderInterface
{

    /**
     * Check given input is supported by the reader.
     *
     * @param mixed                       $input  Reader input.
     * @param ConfigurationInterface|null $config Reading configuration.
     *
     * @return bool
     */
    public function isSupported(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): bool;

    /**
     * Read given input.
     *
     * @param mixed                       $input  Reader input.
     * @param ConfigurationInterface|null $config Reading configuration.
     *
     * @return ResultInterface
     */
    public function read(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface;

}