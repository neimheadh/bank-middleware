<?php

namespace App\Import\Reader;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Result\ResultInterface;

/**
 * Import reader.
 */
abstract class AbstractReader implements ReaderInterface
{

    /**
     * {@inheritDoc}
     */
    public function read(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): ResultInterface {
        if (!$this->isSupported($input)) {
            throw new InputNotSupportedException(
                $this::class,
                $input
            );
        }

        return $this->execute($input, $config);
    }

    /**
     * Read given input.
     *
     * @param mixed                       $input  Reader input.
     * @param ConfigurationInterface|null $config Reading configuration.
     *
     * @return ResultInterface
     */
    abstract protected function execute(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface;

}