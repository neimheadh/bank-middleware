<?php

namespace App\Model\Entity\Generic;

use Neimheadh\SolidBundle\Doctrine\Entity\Date\DatedEntityTrait;
use Neimheadh\SolidBundle\Doctrine\Entity\Index\UniquePrimaryEntityTrait;

/**
 * Application entity trait.
 */
trait EntityTrait
{

    use UniquePrimaryEntityTrait;
    use DatedEntityTrait;
}