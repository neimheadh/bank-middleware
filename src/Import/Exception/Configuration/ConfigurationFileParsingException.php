<?php

namespace App\Import\Exception\Configuration;

use RuntimeException;
use Throwable;

/**
 * Configuration parsing exception.
 */
class ConfigurationFileParsingException extends RuntimeException
{

    public const OBJECT_NO_CLASS_ATTRIBUTE = 1;

    public const OBJECT_CLASS_NOT_FOUND = 2;

    /**
     * @param string         $message  Exception message.
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.
     */
    public function __construct(
        string $message,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf('%s at line %s.', $message, $line),
            $code,
            $previous
        );
    }

}