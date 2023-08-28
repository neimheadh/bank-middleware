<?php

namespace App\Form\Generic\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Transform array data into text.
 */
class ArrayToYamlTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly int $parseFlags = 0,
        private readonly int $dumpFlags = 0,
        private readonly int $inline = 5,
        private readonly int $indent = 2,
    ) {}

    /**
     * Transform a Yaml string into an array.
     *
     * @param mixed|string $value Yaml string.
     *
     * @return array
     */
    public function reverseTransform(mixed $value): array
    {
        if (!is_string($value)) {
            return [];
        }

        return Yaml::parse($value, $this->parseFlags);
    }

    /**
     * Transform an array into yaml string.
     *
     * @param mixed|array $value Input array.
     *
     * @return string
     */
    public function transform(mixed $value): string
    {
        if (!is_array($value)) {
            return '';
        }

        return Yaml::dump(
            $value,
            $this->inline,
            $this->indent,
            $this->dumpFlags,
        );
    }

}