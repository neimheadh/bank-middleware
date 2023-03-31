<?php

namespace App\Import\Processor\Result;

use Countable;
use Iterator;

/**
 * Transform iterator content without processing all values.
 */
class IteratorTransformer implements Iterator, Countable
{

    /**
     * Transform callback.
     *
     * @var callable
     */
    private readonly mixed $transform;

    /**
     * @param Iterator $iterator  Source iterator.
     * @param callable $transform Transform callback.
     */
    public function __construct(
        private readonly Iterator $iterator,
        callable $transform
    ) {
        $this->transform = $transform;
    }

    /**
     * {@inheritDoc}
     */
    public function current(): mixed
    {
        return call_user_func($this->transform, $this->iterator->current());
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        $this->iterator->next();
    }

    /**
     * {@inheritDoc}
     */
    public function key(): mixed
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        if ($this->iterator instanceof Countable) {
            return $this->iterator->count();
        }

        $count = count(iterator_to_array($this->iterator));
        $this->iterator->rewind();
        return $count;
    }

}