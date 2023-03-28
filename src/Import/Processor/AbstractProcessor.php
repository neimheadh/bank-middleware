<?php

namespace App\Import\Processor;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Result\ResultInterface;

/**
 * Import processor.
 */
abstract class AbstractProcessor implements ProcessorInterface
{

    /**
     * {@inheritDoc}
     */
    public function process(
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
     * Process given input.
     *
     * @param mixed                       $input  Processor input.
     * @param ConfigurationInterface|null $config Processing configuration.
     *
     * @return ResultInterface
     */
    abstract public function execute(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface;

}