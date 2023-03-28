<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Missing data map object class exception.
 */
class MissingDataMapObjectClassException extends RuntimeException
{

    /**
     * @param string         $objectKey Object key in data map.
     * @param int            $code      Error code.
     * @param Throwable|null $previous  Previous exception.
     */
    public function __construct(
        string $objectKey,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Missing class for data map object at key "%s".',
            $objectKey
        );

        parent::__construct($message, $code, $previous);
    }

}