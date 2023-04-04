<?php

namespace App\Type\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform an associative array into a flat array of <key, value> array.
 *
 * Go through values to transform/reverse array values the same way.
 */
class AssociativeToFlatArrayTransformer implements DataTransformerInterface
{

    /**
     * {@inheritDoc}
     */
    public function transform(mixed $value): mixed
    {
        if (is_array($value)) {

            $value = array_map(
                fn ($key, $value) => [
                    'key' => $key,
                    'value' => is_array($value)
                        ? $this->transform($value)
                        : $value
                ],
                array_keys($value),
                array_values($value),
            );
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_combine(
                array_map(
                    fn ($entry) => $entry['key'],
                    $value
                ),
                array_map(
                    fn ($entry) => is_array($entry['value'])
                        ? $this->reverseTransform($entry['value'])
                        : $entry['value'],
                    $value
                )
            );
        }

        return $value;
    }

}