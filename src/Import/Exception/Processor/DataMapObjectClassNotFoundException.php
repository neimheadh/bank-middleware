<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Class not found for object in data map exception.
 */
class DataMapObjectClassNotFoundException extends RuntimeException
{

    /**
     * @param string         $objectKey Object key in data map.
     * @param string         $class     Not found class name.
     * @param int            $code      Error code.
     * @param Throwable|null $previous  Previous exception.
     */
    public function __construct(
        string $objectKey,
        string $class,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Class "%s" not found for data map object at key "%s".',
            $class,
            $objectKey
        );

        parent::__construct($message, $code, $previous);
    }

}