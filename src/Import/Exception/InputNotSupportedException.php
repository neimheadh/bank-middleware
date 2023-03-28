<?php

namespace App\Import\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * Import given input is not supported exception.
 */
class InputNotSupportedException extends InvalidArgumentException
{

    /**
     * @param string         $batchClass Class of the batch treating the input.
     * @param mixed          $input      Given input.
     * @param int            $code       Error code.
     * @param Throwable|null $previous   Previous exception.
     */
    public function __construct(
        string $batchClass,
        mixed $input,
        mixed $key = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = $key === null
            ? sprintf(
                'Given input of type "%s" not supported by "%s".',
                gettype($input),
                $batchClass
            )
            : sprintf(
                'An input entry of type "%s" is not supported by "%s" at '
                . 'key "%s".',
                gettype($input),
                $batchClass,
                $key
            );

        parent::__construct($message, $code, $previous);
    }

}