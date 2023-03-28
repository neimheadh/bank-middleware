<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Unknown reference exception.
 */
class UnknownReferenceException extends RuntimeException
{

    /**
     * @param string|null    $objectKey Data map object key.
     * @param string|null    $id        Object identifier.
     * @param int            $code      Error code.
     * @param Throwable|null $previous  Previous exception.
     */
    public function __construct(
        string $objectKey,
        ?string $id,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        if ($id) {
            $message = sprintf(
                'Unknown reference for object "%s" with identifier "%s".',
                $objectKey,
                $id
            );
        } else {
            $message = sprintf(
                'Unknown reference for object "%s".',
                $objectKey,
            );
        }

        parent::__construct($message, $code, $previous);
    }

}