<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Processor parser class does not exist exception.
 */
class ParserClassException extends RuntimeException
{

    /**
     * @param string|null    $class    Parser class name.
     * @param string|null    $item     Item name.
     * @param string|null    $field    Field name.
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.
     */
    public function __construct(
        ?string $class,
        ?string $item,
        ?string $field,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $this->getErrorMessage($class, $item, $field),
            $code,
            $previous
        );
    }

    /**
     * @param string|null $class
     * @param string|null $item
     * @param string|null $field
     *
     * @return string
     */
    protected function getErrorMessage(
        ?string $class,
        ?string $item,
        ?string $field,
    ): string {
        if ($field === null) {
            return sprintf (
                'Class "%s" does not exists for item "%s".',
                $class,
                $item
            );
        }

        return sprintf (
            'Class "%s" does not exists for item "%s", field "%s".',
            $class,
            $item,
            $field
        );
    }

}