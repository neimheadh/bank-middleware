<?php

namespace App\Import\Reader\File;

use App\Import\Configuration\ConfigurationInterface;
use App\Import\Configuration\File\CsvFileImportConfiguration;
use App\Import\Reader\AbstractReader;
use App\Import\Result\ResultCollection;
use App\Import\Result\ResultInterface;
use SplFileObject;

/**
 * CSV file reader.
 */
class CsvFileReader extends AbstractReader
{

    /**
     * @param int $countReadBuffer CSV count line read buffers size.
     */
    public function __construct(
        private readonly int $countReadBuffer = 1024
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(
        mixed $input,
        ?ConfigurationInterface $config = null
    ): bool {
        if ($config !== null
            && !$config instanceof CsvFileImportConfiguration
        ) {
            return false;
        }

        if (($input instanceof SplFileObject && $input->isReadable())
            || is_string($input) && is_readable($input)
        ) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @param CsvFileImportConfiguration $config
     */
    protected function execute(
        mixed $input,
        ?ConfigurationInterface $config
    ): ResultInterface {
        if (!$input instanceof SplFileObject) {
            $input = new SplFileObject($input);
        }

        $header = $config->headed
            ? $this->getLine($input, $config)
            : [];

        return new ResultCollection(
            current: fn() => $this->getLine($input, $config, $header),
            count: function () use ($input, $config) {
                $count = 0;
                $cursor = $input->ftell();

                $input->fseek(0);
                while (!$input->eof()) {
                    $buf = $input->fread($this->countReadBuffer);
                    $count += substr_count(
                        $buf,
                        "\n"
                    );
                }
                $input->fseek($cursor);

                $count -= strlen($buf) - strlen(rtrim($buf, "\n"));

                return $config->headed && $count ? $count - 1 : $count;
            },
            valid: function () use ($input) {
                if ($input->eof()) {
                    return false;
                }
                $cursor = $input->ftell();
                $buf = $input->fread($this->countReadBuffer);
                $input->fseek($cursor);

                if (!trim($buf, "\n")) {
                    return false;
                }

                return true;
            },
            rewind: function() use ($input, $config) {
                $input->fseek(0);

                if ($config->headed) {
                    $this->getLine($input, $config);
                }
            }
        );
    }

    /**
     * Get CSV line.
     *
     * @param SplFileObject              $input  Input file.
     * @param CsvFileImportConfiguration $config Import configuration.
     * @param array|null                 $header CSV header.
     *
     * @return array
     */
    private function getLine(
        SplFileObject $input,
        CsvFileImportConfiguration $config,
        ?array $header = null
    ): array {
        $line = $input->fgetcsv(
            $config->separator,
            $config->enclosure,
            $config->escape
        );

        if ($config->trimValues) {
            $line = array_map(fn (?string $val) => trim($val), $line);
        }

        if ($config->nullify) {
            $line = array_map(
                fn (string $val) => $val === '' ? null : $val,
                $line
            );
        }

        if ($header && $config->headed) {
            $line = array_combine($header, $line);
        }

        return $line;
    }

}