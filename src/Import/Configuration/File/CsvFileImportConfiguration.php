<?php

namespace App\Import\Configuration\File;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Configuration\DataMapConfigurationInterface;

/**
 * CSV file import configuration.
 */
class CsvFileImportConfiguration implements DataMapConfigurationInterface
{

    /**
     * @param string $separator  CSV separator character.
     * @param string $enclosure  CSV enclosure character.
     * @param string $escape     CSV escape character.
     * @param bool   $headed     Is the CSV file have a header?
     * @param bool   $trimValues Do we have to trim the CSV values?
     * @param bool   $nullify    Nullify empty string.
     * @param array  $dataMap    File to entities data map.
     */
    public function __construct(
        public readonly string $separator = ',',
        public readonly string $enclosure = '"',
        public readonly string $escape = '\\',
        public readonly bool $headed = true,
        public readonly bool $trimValues = true,
        public readonly bool $nullify = true,
        public readonly array $dataMap = [],
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getDataMap(): array
    {
        return $this->dataMap;
    }

}