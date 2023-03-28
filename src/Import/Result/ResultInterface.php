<?php

namespace App\Import\Result;

use Countable;
use Iterator;

/**
 * Import result.
 */
interface ResultInterface extends Countable,
                                  Iterator
{

}