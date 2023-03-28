<?php

namespace App\Import\Result;

/**
 * Import result collection.
 */
class ResultCollection implements ResultInterface
{

    /**
     * Count callback.
     *
     * @var callable
     */
    private readonly mixed $_count;

    /**
     * Current callback.
     *
     * @var callable
     */
    private readonly mixed $_current;

    /**
     * Key callback.
     *
     * @var callable|null
     */
    private readonly mixed $_key;

    /**
     * Next callback.
     *
     * @var callable|null
     */
    private readonly mixed $_next;

    /**
     * Rewind callback.
     *
     * @var callable|null
     */
    private readonly mixed $_rewind;

    /**
     * Valid callback.
     *
     * @var callable|null
     */
    private readonly mixed $_valid;

    /**
     * Current index.
     *
     * @var int
     */
    private int $index = 0;

    /**
     * Create a new result from another one.
     *
     * @param ResultCollection $model   Result model.
     * @param callable|null    $current Current callback.
     * @param callable|null    $count   Count callback.
     * @param callable|null    $next    Next callback.
     * @param callable|null    $key     Key callback.
     * @param callable|null    $valid   Valid callback.
     * @param callable|null    $rewind  Rewind callback.
     *
     * @return ResultCollection
     */
    public static function from(
        ResultCollection $model,
        callable $current = null,
        callable $count = null,
        callable $next = null,
        callable $key = null,
        callable $valid = null,
        callable $rewind = null
    ): ResultCollection {
        return new ResultCollection(
            $current ?: $model->_current,
            $count ?: $model->_count,
            $next ?: $model->_next,
            $key ?: $model->_key,
            $valid ?: $model->_valid,
            $rewind ?: $model->_rewind,
        );
    }

    /**
     * @param callable      $current Current callback.
     * @param callable      $count   Count callback.
     * @param callable|null $next    Next callback.
     * @param callable|null $key     Key callback.
     * @param callable|null $valid   Valid callback.
     * @param callable|null $rewind  Rewind callback.
     */
    public function __construct(
        callable $current,
        callable $count,
        callable $next = null,
        callable $key = null,
        callable $valid = null,
        callable $rewind = null,
    ) {
        $this->_current = $current;
        $this->_count = $count;
        $this->_next = $next;
        $this->_key = $key;
        $this->_valid = $valid;
        $this->_rewind = $rewind;
    }

    /**
     * {@inheritDoc}
     */
    public function current(): mixed
    {
        return call_user_func($this->_current);
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        if ($this->_next !== null) {
            call_user_func($this->_next);
        }
        ++$this->index;
    }

    /**
     * {@inheritDoc}
     */
    public function key(): mixed
    {
        return $this->_key === null
            ? $this->index
            : call_user_func($this->_key);
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->_valid === null
            ? $this->index < $this->count()
            : call_user_func($this->_valid);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        if ($this->_rewind !== null) {
            call_user_func($this->_rewind);
        }
        $this->index = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return call_user_func($this->_count);
    }

}