<?php

namespace App\Import\Processor;

use Throwable;

/**
 * Import processor.
 *
 * @template T
 * @implements ProcessorInterface<T>
 */
abstract class AbstractProcessor implements ProcessorInterface
{

    /**
     * {@inheritDoc}
     *
     * @return T
     * @throws Throwable
     */
    public function process(
        mixed $input,
        array $options = [],
    ): mixed {
        if (!$this->isSupported($input, $options)) {
            throw $this->getUnsupportedException($input, $options);
        }

        return $this->execute($input, $options);
    }

    /**
     * Process given input.
     *
     * @param mixed $input   Processor input.
     * @param array $options Processor options.
     *
     * @return T
     */
    abstract protected function execute(mixed $input, array $options): mixed;

    /**
     * Get the input not supported exception.
     *
     * @param mixed $input   Given input.
     * @param array $options Processor options.
     *
     * @return Throwable
     */
    abstract protected function getUnsupportedException(
        mixed $input,
        array $options
    ): Throwable;

}