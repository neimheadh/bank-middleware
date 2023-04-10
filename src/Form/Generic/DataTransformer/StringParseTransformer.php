<?php

namespace App\Form\Generic\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Form string or array of string interpretation transformer.
 */
class StringParseTransformer implements DataTransformerInterface
{

    /**
     * {@inheritDoc}
     */
    public function transform(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_map(
                fn($entry) => $this->transform($entry),
                $value
            );
        } elseif (is_int($value) || is_float($value)) {
            $value = "$value";
        } else {
            $value = match ($value) {
                true => 'TRUE',
                false => 'FALSE',
                null => 'NULL',
                default => $value
            };
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_map(
                fn($entry) => $this->reverseTransform($entry),
                $value
            );
        } elseif (is_numeric($value)) {
            $float = floatval($value);
            $int = intval($value);

            $value = $int == $float ? $int : $float;
        } elseif (is_string($value)) {
            $value = match (strtolower($value)) {
                'true' => true,
                'false' => false,
                'null' => null,
                default => $value,
            };
        }

        return $value;
    }

}