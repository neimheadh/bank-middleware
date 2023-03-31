<?php

namespace App\Import\Reader;

use App\Import\Exception\InputNotSupportedException;
use App\Import\Reader\Result\CsvFileIterator;
use SplFileObject;
use Throwable;

/**
 * CSV file reader.
 *
 * @extends AbstractReader<CsvFileIterator>
 */
class CsvFileReader extends AbstractReader
{

    /**
     * Default options.
     */
    public const DEFAULT_OPTIONS = [
        self::OPTION_ENCLOSURE => '"',
        self::OPTION_ESCAPE => '\\',
        self::OPTION_HEADED => true,
        self::OPTION_SEPARATOR => ',',
    ];

    /**
     * Enclosure character option name.
     */
    public const OPTION_ENCLOSURE = 'enclosure';

    /**
     * Escape character option name.
     */
    public const OPTION_ESCAPE = 'escape';

    /**
     * Is the CSV headed option name.
     */
    public const OPTION_HEADED = 'headed';

    /**
     * Separator character option name.
     */
    public const OPTION_SEPARATOR = 'separator';

    /**
     * {@inheritDoc}
     */
    public function isSupported(
        mixed $input,
        array $options = [],
    ): bool {
        return is_string($input) || $input instanceof SplFileObject;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(mixed $input, array $options): CsvFileIterator
    {
        $options = array_merge(self::DEFAULT_OPTIONS, $options);
        $input = is_string($input)
            ? new SplFileObject($input, 'r')
            : $input;

        return new CsvFileIterator(
            file: $input,
            separator: $options[self::OPTION_SEPARATOR],
            enclosure: $options[self::OPTION_ENCLOSURE],
            escape: $options[self::OPTION_ESCAPE],
            headed: $options[self::OPTION_HEADED]
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getUnsupportedException(
        mixed $input,
        array $options
    ): Throwable {
        return new InputNotSupportedException($this::class, $input);
    }

}