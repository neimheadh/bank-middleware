<?php

namespace App\Import\Writer;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Result\ResultInterface;

/**
 * Import writer.
 */
abstract class AbstractWriter implements WriterInterface
{

    /**
     * {@inheritDoc}
     */
    public function write(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): ResultInterface {
        if (!$this->isSupported($input, $config)) {
            throw new InputNotSupportedException(
                $this::class,
                $input
            );
        }

        return $this->execute($input, $config);
    }

    /**
     * Write given input.
     *
     * @param mixed                       $input  Writer input.
     * @param ConfigurationInterface|null $config Writer configuration.
     *
     * @return ResultInterface
     */
    abstract public function execute(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface;

}