<?php

namespace App\Type\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Form array to yaml string transformer.
 */
class ArrayToYamlTransformer implements DataTransformerInterface
{

    /**
     * @param int $inline      Dump inline count.
     * @param int $indent      Dump indent size.
     * @param int $parseFlags  Parse flags.
     * @param int $dumpFlags   Dump flags.
     */
    public function __construct(
        public readonly int $inline = 2,
        public readonly int $indent = 4,
        public readonly int $parseFlags = 0,
        public readonly int $dumpFlags = 0,
    )
    {
    }

    /**
     * {@inheritDoc}
     */
    public function transform(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = Yaml::dump(
                $value,
                $this->inline,
                $this->indent,
                $this->dumpFlags,
            );
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = Yaml::parse(
                $value,
                $this->parseFlags,
            );
        }

        return $value;
    }

}