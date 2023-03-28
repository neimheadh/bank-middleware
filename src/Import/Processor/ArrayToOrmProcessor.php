<?php

namespace App\Import\Processor;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Configuration\DataMapConfigurationInterface;
use App\Import\Exception\InputNotSupportedException;
use App\Import\Exception\Processor\DataMapObjectClassNotFoundException;
use App\Import\Exception\Processor\MissingDataMapObjectClassException;
use App\Import\Exception\Processor\UnknownEntryFieldException;
use App\Import\Exception\Processor\UnknownReferenceException;
use App\Import\Parser\ParserInterface;
use App\Import\Result\ResultCollection;
use App\Import\Result\ResultInterface;
use Countable;
use Iterator;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Processor transforming array data into ORM entity.
 */
class ArrayToOrmProcessor extends AbstractProcessor
{

    /**
     * Referenced objects.
     *
     * @var array
     */
    private array $references = [];

    /**
     * {@inheritDoc}
     */
    public function isSupported(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): bool {
        return $input instanceof Iterator
            && $input instanceof Countable
            && $config instanceof DataMapConfigurationInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @param Iterator|Countable            $input  Processor input.
     * @param DataMapConfigurationInterface $config Processor configuration.
     *
     */
    public function execute(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface {
        return new ResultCollection(
            current: function () use ($input, $config) {
                $entry = $input->current();

                if (!is_array($entry)) {
                    new InputNotSupportedException(
                        $this::class,
                        $entry,
                        $input->key()
                    );
                }

                return $this->parseMap($config->getDataMap(), $entry);
            },
            count: fn() => $input->count(),
            next: fn() => $input->next(),
            key: fn() => $input->key(),
            valid: fn() => $input->valid(),
            rewind: fn() => $input->rewind(),
        );
    }

    /**
     * Get result entry field.
     *
     * @param string $field Field name.
     * @param array  $entry Result entry.
     *
     * @return mixed
     */
    private function getEntryField(string $field, array $entry): mixed
    {
        if (!array_key_exists($field, $entry)) {
            throw new UnknownEntryFieldException($field, $entry);
        }

        return $entry[$field];
    }

    /**
     * Parse data map to apply given entry data.
     *
     * @param array $dataMap The base data map.
     * @param array $entry   The input data entry.
     *
     * @return array
     */
    private function parseMap(array $dataMap, array $entry): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return array_map(
            function ($key, $conf) use ($accessor, $entry) {
                $this->references[$key] = $this->references[$key] ?? [];

                $attributes = $conf['attributes'] ?? [];
                $map = $conf['map'] ?? [];

                $class = $conf['class']
                    ?? throw new MissingDataMapObjectClassException($key);

                if (class_exists($class)) {
                    $objectOrArray = new $class(...$attributes);
                } else {
                    throw new DataMapObjectClassNotFoundException($key, $class);
                }

                if (isset($conf['identifier'])) {
                    $objectOrArray = $this->references[$key][$conf['identifier']]
                        ?? $objectOrArray;
                    $this->references[$key][$conf['identifier']] = $objectOrArray;
                }

                foreach ($map as $propertyPath => $mapConf) {
                    $accessor->setValue(
                        $objectOrArray,
                        $propertyPath,
                        $this->parseMapValue($mapConf, $entry)
                    );
                }

                return $objectOrArray;
            },
            array_keys($dataMap),
            $dataMap
        );
    }

    /**
     * Parse a value from a data map value configuration.
     *
     * @param array $config Data map value confiugration.
     * @param array $entry  Read entry.
     *
     * @return mixed
     */
    private function parseMapValue(array $config, array $entry): mixed
    {
        $value = null;

        if (isset($config['field'])) {
            $field = $config['field'];

            if (is_array($field)) {
                $value = array_map(
                    fn($field) => $this->getEntryField($field, $entry),
                    $field
                );
            } else {
                $value = $this->getEntryField($field, $entry);
            }
        }

        if (isset($config['reference'])) {
            [$key, $id] = array_pad(
                explode('@', $config['reference']),
                2,
                null
            );

            if (!isset($this->references[$key])
                || !isset($this->references[$key][$id])
            ) {
                throw new UnknownReferenceException(
                    $key,
                    $id
                );
            }

            $value = $this->references[$key][$id];
        }

        if (isset($config['value'])) {
            $value = $config['value'];
        }

        if (isset($config['parser'])
            && $config['parser'] instanceof ParserInterface
        ) {
            $value = $config['parser']->parse($value);
        }

        return $value;
    }

}