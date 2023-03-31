<?php

namespace App\Import\Exception\Processor;

use Throwable;

/**
 * Parser class is missing in the data map.
 */
class MissingParserClassException extends ParserClassException
{

    /**
     * @param string         $item     Item name.
     * @param string|null    $field    Field name.
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.
     */
    public function __construct(
        string $item,
        ?string $field = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(null, $item, $field, $code, $previous);
    }

    /**
     * {@inheritDoc}
     */
    protected function getErrorMessage(
        ?string $class,
        ?string $item,
        ?string $field,
    ): string {
        if ($field) {
            return sprintf(
                'Parser class missing in data map for item "%s",'
                . ' field "%s".',
                $item,
                $field
            );
        }

        return sprintf(
            'Parser class missing in data map for item "%s".',
            $item
        );
    }

}