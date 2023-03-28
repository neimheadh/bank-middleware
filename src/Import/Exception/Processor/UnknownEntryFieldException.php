<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Unknown import result entry field exception.
 */
class UnknownEntryFieldException extends RuntimeException
{

    /**
     * @param string         $field    Unknown field.
     * @param array          $entry    Import result entry.
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.
     */
    public function __construct(
        string $field,
        array $entry,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Unknown entry field "%s", available are "%s".',
            $field,
            implode('", "', array_keys($entry))
        );

        parent::__construct($message, $code, $previous);
    }

}