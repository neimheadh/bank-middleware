<?php

namespace App\Filesystem\Reader;

use SplFileObject;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CSV file reader.
 */
class CsvReader
{

    /**
     * CSV enclosure option name.
     */
    public const OPTION_ENCLOSURE = 'enclosure';

    /**
     * CSV escape option name.
     */
    public const OPTION_ESCAPE = 'escape';

    /**
     * CSV has header option name.
     */
    public const OPTION_HAS_HEADER = 'has-header';

    /**
     * CSV separator option name.
     */
    public const OPTION_SEPARATOR = 'separator';

    /**
     * Default options.
     */
    private const DEFAULT_OPTIONS = [
      self::OPTION_SEPARATOR => ',',
      self::OPTION_ESCAPE => '\\',
      self::OPTION_ENCLOSURE => '"',
      self::OPTION_HAS_HEADER => true,
    ];

    /**
     * Process csv file.
     *
     * @param string               $file     CSV file path.
     * @param array                $options  Import options.
     * @param OutputInterface|null $output   Console output.
     * @param callable|null        $callable Callable for each line.
     *
     * @return void
     */
    public function process(
      string $file,
      array $options = [],
      ?OutputInterface $output = null,
      ?callable $callable = null,
    ): void {
        $options = array_merge(self::DEFAULT_OPTIONS, $options);
        $file = new SplFileObject($file, 'r');
        $progress = $output ? new ProgressBar($output) : null;

        $progress?->setFormat('[%bar%] %percent%% - %remaining%/%estimated%');

        $file->fseek(0, SEEK_END);
        $progress?->setMaxSteps($file->ftell());
        $file->fseek(0, SEEK_SET);

        $header = null;
        $progress?->start();
        while (!$file->eof()) {
            $line = $file->fgetcsv(
              $options[self::OPTION_SEPARATOR],
              $options[self::OPTION_ENCLOSURE],
              $options[self::OPTION_ESCAPE],
            );

            if ($header === null && $options[self::OPTION_HAS_HEADER]) {
                $header = $line;
            } else {
                if ($header !== null) {
                    $line = array_pad($line, count($header), null);
                    $line = array_combine($header, $line);
                }
                $callable($line);
            }

            $progress->setProgress($file->ftell());
        }
        $progress?->finish();
        $output?->writeln('');
        unset($file);
    }

}