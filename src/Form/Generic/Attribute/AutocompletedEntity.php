<?php

namespace App\Form\Generic\Attribute;

use Attribute;

/**
 * Autocompleted entity attribute.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AutocompletedEntity
{

    /**
     * @param string $field      Entity autocomplete label field name.
     * @param string $primaryKey Primary key field name.
     */
    public function __construct(
        public readonly string $field,
        public readonly string $primaryKey = 'id'
    ) {
    }

}