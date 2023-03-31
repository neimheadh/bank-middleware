<?php

namespace App\Import\Exception\Processor;

use InvalidArgumentException;
use Throwable;

/**
 * Parser input not supported exception.
 */
class ParserInputNotSupportedException extends InvalidArgumentException
{

    /**
     * @param string         $parserClass Class of the parser treating the input.
     * @param mixed          $input       Given input.
     * @param int            $code        Error code.
     * @param Throwable|null $previous    Previous exception.
     */
    public function __construct(
        string $parserClass,
        mixed $input,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Given input of type "%s" not supported by "%s".',
            gettype($input),
            $parserClass
        );

        parent::__construct($message, $code, $previous);
    }

}