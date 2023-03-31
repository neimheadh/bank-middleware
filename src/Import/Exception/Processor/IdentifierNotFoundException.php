<?php

namespace App\Import\Exception\Processor;

use RuntimeException;
use Throwable;

/**
 * Identifier not found in data map.
 */
class IdentifierNotFoundException extends RuntimeException
{

    /**
     * @param string         $item       Item name.
     * @param string         $identifier Item identifier.
     * @param int            $code       Error code.
     * @param Throwable|null $previous   Previous exception.
     */
    public function __construct(
        string $item,
        string $identifier,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $this->getErrorMessage(
                $item,
                $identifier
            ),
            $code,
            $previous
        );
    }

    /**
     * Get error message.
     *
     * @param string $item       Item name.
     * @param string $identifier Item identifier.
     *
     * @return string
     */
    protected function getErrorMessage(
        string $item,
        string $identifier
    ): string {
        return sprintf(
            'Identifier for item %s "%s" not found in data map.',
            $item,
            $identifier
        );
    }

}