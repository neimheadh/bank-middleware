<?php

namespace App\Import\Reader;

use App\Import\Exception\InputNotSupportedException;
use Throwable;

/**
 * Import reader.
 *
 * @template T
 *
 * @implements ReaderInterface<T>
 */
abstract class AbstractReader implements ReaderInterface
{

    /**
     * {@inheritDoc}
     *
     * @return T
     * @throws Throwable
     */
    public function read(
        mixed $input,
        array $options = [],
    ): mixed {
        if (!$this->isSupported($input, $options)) {
            throw $this->getUnsupportedException($input, $options);
        }

        return $this->execute($input, $options);
    }

    /**
     * Read given input.
     *
     * @param mixed $input   Reader input.
     * @param array $options Reading options.
     *
     * @return T
     */
    abstract protected function execute(mixed $input, array $options): mixed;

    /**
     * Get the file not supported exception.
     *
     * @param mixed $input   Given input.
     * @param array $options Reading options.
     *
     * @return Throwable
     */
    abstract protected function getUnsupportedException(
        mixed $input,
        array $options
    ): Throwable;

}