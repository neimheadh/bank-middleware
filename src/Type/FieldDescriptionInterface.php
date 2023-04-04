<?php

namespace App\Type;

use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface as Base;

/**
 * Field description.
 */
interface FieldDescriptionInterface extends Base
{
    public const TYPE_BALANCE = 'balance';
}