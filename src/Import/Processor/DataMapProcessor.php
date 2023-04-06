<?php

namespace App\Import\Processor;

use App\Import\Exception\InputNotSupportedException;
use App\Import\Exception\MissingConfigurationException;
use App\Import\Exception\Processor\IdentifierNotFoundException;
use App\Import\Exception\Processor\ItemClassException;
use App\Import\Exception\Processor\MissingFieldException;
use App\Import\Exception\Processor\MissingParserClassException;
use App\Import\Exception\Processor\ParserClassException;
use App\Import\Processor\Parser\ParserInterface;
use App\Import\Processor\Result\IteratorTransformer;
use Iterator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Throwable;
use Traversable;

/**
 * Processor transforming input into ORM entities.
 *
 * @extends AbstractProcessor<object[]>
 */
class DataMapProcessor extends AbstractProcessor
{

    /**
     * Data map option.
     */
    public const OPTION_DATA_MAP = 'data-map';

    /**
     * Property accessor.
     *
     * @var PropertyAccessor
     */
    private PropertyAccessor $accessor;

    /**
     * Identifiers stack.
     *
     * @var array
     */
    private array $identifiers;

    /**
     * @param ContainerInterface $container Application container.
     */
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(mixed $input, array $options = []): bool
    {
        return is_array($options[self::OPTION_DATA_MAP] ?? null)
            && is_iterable($input);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(
        mixed $input,
        array $options
    ): array|IteratorTransformer {
        $dataMap = $options[self::OPTION_DATA_MAP];
        $this->identifiers = [];

        if ($input instanceof Iterator) {
            $items = new IteratorTransformer(
                $input,
                function ($item) use ($input, $dataMap) {
                    return is_string($item)
                        ? $this->parseDataMap(
                            [$input->key() => $item],
                            $dataMap
                        )
                        : $this->parseDataMap(
                            $item,
                            $dataMap
                        );
                }
            );
        } else {
            $input = $this->isMultipleItemInput($input) ? $input : [$input];
            $items = [];
            foreach ($input as $item) {
                $items[] = $this->parseDataMap(
                    $item,
                    $dataMap
                );
            }
        }

        return $items;
    }

    /**
     * {@inheritDoc}
     */
    protected function getUnsupportedException(
        mixed $input,
        array $options
    ): Throwable {
        if (($options[self::OPTION_DATA_MAP] ?? null) === null) {
            return new MissingConfigurationException(
                $this::class,
                self::OPTION_DATA_MAP,
            );
        }

        return new InputNotSupportedException(
            $this::class,
            $input
        );
    }

    /**
     * Test if the input is a multiple item input.
     *
     * @param iterable $input Given input.
     *
     * @return bool
     */
    private function isMultipleItemInput(iterable $input): bool
    {
        foreach ($input as $entry) {
            if (!is_iterable($entry)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Parse class arguments.
     *
     * @param array $arguments Argument list.
     *
     * @return array
     */
    private function parseArguments(array $arguments): array
    {
        return array_map(
            function ($argument) {
                if (is_string($argument) && str_starts_with($argument, '@')) {
                    return $this->container->get(substr($argument, 1));
                }

                if (is_array($argument)
                    && array_key_exists('class', $argument)
                ) {
                    $arguments = $this->parseArguments(
                        $argument['arguments'] ?? []
                    );
                    $class = $argument['class'];
                    return new $class(...$arguments);
                }

                return $argument;
            },
            $arguments
        );
    }

    /**
     * Parse data map.
     *
     * @param mixed $input   Processor input.
     * @param array $dataMap Data map.
     *
     * @return array
     */
    private function parseDataMap(
        mixed $input,
        array $dataMap
    ): array {
        $items = [];

        foreach ($dataMap as $item => $map) {
            $parser = null;

            $this->identifiers[$item] = $this->identifiers[$item] ?? [];

            if (isset($map['parser'])) {
                $parser = $this->parseParser(
                    item: $item,
                    className: $map['parser']['class'] ?? null,
                    arguments: $map['parser']['arguments'] ?? [],
                );
            }

            $items[] = $this->parseItem(
                identifier: $map['identifier'] ?? null,
                item: $item,
                className: $map['class'] ?? $item,
                arguments: $map['arguments'] ?? [],
                parser: $parser,
                dataMap: $map['map'] ?? [],
                input: $input
            );
        }

        return $items;
    }

    /**
     * Parse an item field.
     *
     * @param string               $item      Item name.
     * @param string               $field     Field name.
     * @param string|array|null    $value     Field name in input.
     * @param string|null          $reference Referenced field.
     * @param mixed                $fixed     Fixed value.
     * @param ParserInterface|null $parser    Value parser.
     * @param iterable             $input     Processor input entry.
     *
     * @return mixed
     */
    private function parseField(
        string $item,
        string $field,
        string|array|null $value,
        ?string $reference,
        mixed $fixed,
        ?ParserInterface $parser,
        iterable $input,
    ): mixed {
        $data = $input instanceof Traversable
            ? iterator_to_array($input)
            : $input;

        foreach (is_array($value) ? $value : [$value] as $entry) {
            if ($entry !== null && !array_key_exists($entry, $data)) {
                throw new MissingFieldException(
                    $item,
                    $field,
                    $entry,
                    $data
                );
            }
        }

        if ($value !== null) {
            $value = is_array($value)
                ? array_map(fn($entry) => $data[$entry], $value)
                : $data[$value];
        } elseif ($reference !== null) {
            [$name, $id] = explode('.', $reference);

            if (!array_key_exists($name, $this->identifiers)
                || !array_key_exists($id, $this->identifiers[$name])
            ) {
                throw new IdentifierNotFoundException($name, $id);
            }

            $value = $this->identifiers[$name][$id];
        } else {
            $value = $fixed;
        }

        return $parser ? $parser->parse($value) : $value;
    }

    /**
     * Parse an item.
     *
     *
     * @param string|null          $identifier Item identifier.
     * @param string               $item       Item name.
     * @param string               $className  Item class name.
     * @param array                $arguments  Item constructor arguments.
     * @param ParserInterface|null $parser     Value parser.
     * @param array                $dataMap    Item fields data map.
     * @param iterable             $input      Processor input entry.
     *
     * @return object
     */
    private function parseItem(
        ?string $identifier,
        string $item,
        string $className,
        array $arguments,
        ?ParserInterface $parser,
        array $dataMap,
        mixed $input,
    ): object {
        if (!class_exists($className)) {
            throw new ItemClassException($className, $item);
        }

        $arguments = $this->parseArguments($arguments);
        $instance = $parser
            ? $parser->parse($input)
            : new $className(...$arguments);

        foreach ($dataMap as $field => $map) {
            $parser = ($map['parser'] ?? null) !== null
                ? $this->parseParser(
                    item: $item,
                    className: $map['parser']['class'] ?? null,
                    arguments: $map['parser']['arguments'] ?? [],
                    field: $field
                ) : null;

            $this->accessor->setValue(
                $instance,
                $field,
                $this->parseField(
                    item: $item,
                    field: $field,
                    value: is_string($map) ? $map : ($map['value'] ?? null),
                    reference: $map['reference'] ?? null,
                    fixed: $map['fixed'] ?? null,
                    parser: $parser,
                    input: $input
                )
            );
        }


        if ($identifier) {
            $id = $this->accessor->getValue($instance, $identifier);

            if (isset($this->identifiers[$item][$id])) {
                return $this->identifiers[$item][$id];
            }

            $this->identifiers[$item][$id] = $instance;
        }

        return $instance;
    }

    /**
     * Parse data map parser object.
     *
     * @param string      $item      Item name.
     * @param string|null $className Parser class.
     * @param array       $arguments Parser constructor arguments.
     * @param string|null $field     Field name.
     *
     * @return ParserInterface
     */
    private function parseParser(
        string $item,
        ?string $className,
        array $arguments,
        ?string $field = null
    ): ParserInterface {
        if (is_null($className)) {
            throw new MissingParserClassException(
                $item,
                $field
            );
        }

        if (!class_exists($className)) {
            throw new ParserClassException(
                $className,
                $item,
                $field,
            );
        }

        $arguments = $this->parseArguments($arguments);
        return new $className(...$arguments);
    }

}