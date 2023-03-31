<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Item class does not exist exception.
 */
class ItemClassException extends RuntimeException
{

    /**
     * @param string         $class    Item class.
     * @param string         $name     Item name.
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.s
     */
    public function __construct(
        string $class,
        string $name,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $this->getErrorMessage($class, $name),
            $code,
            $previous
        );
    }

    /**
     * Get error message.
     *
     * @param string $class Item class.
     * @param string $name  Item name.
     *
     * @return string
     */
    protected function getErrorMessage(
        string $class,
        string $name
    ): string {
        return sprintf(
            'Class "%s" for item "%s" does not exist.',
            $class,
            $name
        );
    }

}