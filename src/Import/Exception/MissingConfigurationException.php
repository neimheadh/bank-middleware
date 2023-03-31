<?php

namespace App\Import\Exception;

use RuntimeException;
use Throwable;

/**
 * Missing configuration exception.
 */
class MissingConfigurationException extends RuntimeException
{

    /**
     * @param string         $batchClass Batch class.
     * @param string         $name       Missing configuration name.
     * @param int            $code       Error code.
     * @param Throwable|null $previous   Previous exception.
     */
    public function __construct(
        string $batchClass,
        string $name,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Missing configuration "%s" for %s.',
            $name,
            $batchClass
        );

        parent::__construct($message, $code, $previous);
    }

}