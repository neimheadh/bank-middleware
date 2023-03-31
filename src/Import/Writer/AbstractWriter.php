<?php

namespace App\Import\Writer;

use Throwable;

/**
 * Import writer.
 *
 * @template T
 * @implements WriterInterface<T>
 */
abstract class AbstractWriter implements WriterInterface
{

    /**
     * {@inheritDoc}
     *
     * @return T
     * @throws Throwable
     */
    public function write(mixed $input, array $options = []): mixed
    {
        if (!$this->isSupported($input, $options)) {
            throw $this->getUnsupportedException(
                $input,
                $options
            );
        }

        return $this->execute($input, $options);
    }

    /**
     * Write given input.
     *
     * @param mixed $input   Writer input.
     * @param array $options Writer options.
     */
    abstract protected function execute(mixed $input, array $options): mixed;

    /**
     * Get the input not supported exception.
     *
     * @param mixed $input   Given input.
     * @param array $options Writer options.
     *
     * @return Throwable
     */
    abstract protected function getUnsupportedException(
        mixed $input,
        array $options
    ): Throwable;

}