<?php

namespace App\Import\Reader\Result;

use Countable;
use Iterator;
use SplFileObject;

/**
 * CSV file iterator.
 */
class CsvFileIterator implements Countable, Iterator
{

    /**
     * End of file cursor.
     *
     * @var int
     */
    private int $eof;

    /**
     * CSV file.
     *
     * @var SplFileObject
     */
    private SplFileObject $file;

    /**
     * CSV heading line.
     *
     * @var array|null
     */
    private ?array $header = null;

    /**
     * Currently read CSV line.
     *
     * @var int
     */
    private int $currentLine = 0;

    /**
     * Next CSV line cursor.
     *
     * @var int
     */
    private int $nextLine = 0;

    /**
     * @param string|SplFileObject $file       CSV line.
     * @param string               $separator  Separator character.
     * @param string               $enclosure  Enclosure character.
     * @param string               $escape     Escape character.
     * @param bool                 $headed     Is the CSV file headed.
     * @param int                  $bufferSize File reading buffer size.
     *                                         Used on CSV size count
     *                                         calculation.
     */
    public function __construct(
        string|SplFileObject $file,
        private readonly string $separator = ',',
        private readonly string $enclosure = '"',
        private readonly string $escape = '\\',
        private readonly bool $headed = true,
        private readonly int $bufferSize = 4096,
    ) {
        $this->file = is_string($file)
            ? new SplFileObject($file, 'r')
            : $file;

        $this->file->fseek(0, SEEK_END);
        $this->eof = $this->file->ftell();
        $this->file->fseek(0);
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function count(): int
    {
        $cursor = $this->file->ftell();
        $this->file->seek(0);
        $count = $this->headed ? -1 : 0;

        while (!$this->file->eof()) {
            $buffer = $this->file->fread($this->bufferSize);
            $count += substr_count($buffer, "\n");
        }

        $this->file->fseek($cursor);

        return max($count, 0);
    }

    /**
     * {@inheritDoc}
     */
    public function current(): ?array
    {
        if ($this->file->eof()) {
            return null;
        }

        if ($this->headed && $this->header === null) {
            $this->header = $this->fgetcsv(true);
            return $this->current();
        }

        $line = $this->fgetcsv(false);

        if (empty($line) || $line === [null]) {
            return null;
        }

        return $this->getLine($line);
    }

    /**
     * {@inheritDoc}
     */
    public function key(): int
    {
        return $this->currentLine;
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        $this->file->fseek($this->nextLine);
        $this->currentLine++;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->file->fseek(0);
        $this->nextLine = 0;
        $this->header = null;
        $this->currentLine = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->file->ftell() < $this->eof && !$this->file->eof();
    }

    /**
     * Get the csv line.
     *
     * @param bool $moveCursor Move the file cursor if true.
     *
     * @return array
     */
    private function fgetcsv(bool $moveCursor): array
    {
        $cursor = $this->file->ftell();
        $line = $this->file->fgetcsv(
            $this->separator,
            $this->enclosure,
            $this->escape
        );

        if (!$moveCursor) {
            $this->nextLine = $this->file->ftell();
            $this->file->fseek($cursor);
        }

        return $line;
    }

    /**
     * Get the csv line with header if specified.
     *
     * @param array $line The line without keys.
     *
     * @return array
     */
    private function getLine(array $line): array
    {
        if ($this->header) {
            $header = count($this->header) >= count($line)
                ? $this->header
                : array_merge(
                    $this->header,
                    range(count($this->header), count($line) - 1)
                );
            $line = count($line) === count($header)
                ? $line
                : array_merge(
                    $line,
                    array_fill(0, count($header) - count($line), null)
                );

            return array_combine($header, $line);
        }

        return $line;
    }

}