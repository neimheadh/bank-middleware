<?php

namespace App\Exception\Entity\Account;

use InvalidArgumentException;
use Throwable;

/**
 * Account entity not found exception.
 */
class AccountNotFoundException extends InvalidArgumentException
{

    /**
     * @param array          $constraint
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
      array $constraint = [],
      int $code = 0,
      ?Throwable $previous = null
    ) {
        $message = 'Account';

        if (!empty($constraint)) {
            $message .= ' [';
            foreach ($constraint as $field => $value) {
                $message .= sprintf('"%s"="%s"', $field, $value);
            }
            $message .= ']';
        }

        $message .= ' not found.';

        parent::__construct(
          $message,
          $code,
          $previous
        );
    }

}