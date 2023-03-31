<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Requested field is missing in input.
 */
class MissingFieldException extends RuntimeException
{

    /**
     * @param string         $item     Item name.
     * @param string         $field    Field name.
     * @param string         $value    Input field name.
     * @param array          $input    Input.
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.
     */
    public function __construct(
        string $item,
        string $field,
        string $value,
        array $input,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $this->getErrorMessage(
                $item,
                $field,
                $value,
                $input
            ),
            $code,
            $previous
        );
    }

    /**
     * Get error message.
     *
     * @param string $item  Item name.
     * @param string $field Field name.
     * @param string $value Input field name.
     * @param array  $input Input.
     *
     * @return string
     */
    protected function getErrorMessage(
        string $item,
        string $field,
        string $value,
        array $input
    ): string {
        return sprintf(
            'Input "%s" field missing for item "%s", field "%s".'
            . ' Existing: "%s".',
            $value,
            $item,
            $field,
            implode('", "', array_keys($input))
        );
    }

}