<?php

namespace App\Exception\Import;

use InvalidArgumentException;
use Throwable;

/**
 * Unknown CSV model exception.
 */
class UnknownCsvModelException extends InvalidArgumentException
{

    /**
     * {@inheritDoc}
     *
     * @param string $model CSV model.
     */
    public function __construct(
      string $model = "",
      int $code = 0,
      ?Throwable $previous = null
    ) {
        parent::__construct(
          sprintf('Unknown CSV model "%s".', $model),
          $code,
          $previous
        );
    }

}