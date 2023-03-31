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
     * @param mixed          $key        Error position key.
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
        $message = $this->buildMessage($batchClass, $input, $key);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Build error message.
     *
     * @param string $batchClass Class of the batch treating the input.
     * @param mixed  $input      Given input.
     * @param mixed  $options    Given options.
     * @param mixed  $key        Error position key.
     *
     * @return string
     */
    protected function buildMessage(
        string $batchClass,
        mixed $input,
        mixed $key = null,
    ): string {
        return $key === null
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
    }

}