<?php

namespace App\Model\Entity\Generic;

use Neimheadh\SolidBundle\Doctrine\Entity\Date\DatedEntityInterface;
use Neimheadh\SolidBundle\Doctrine\Entity\Index\UniquePrimaryEntityInterface;

/**
 * Application entity.
 */
interface EntityInterface extends UniquePrimaryEntityInterface,
                                  DatedEntityInterface
{
}