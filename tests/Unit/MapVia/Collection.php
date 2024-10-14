<?php

namespace Tests\Unit\MapVia;

class Collection
{
    public array $items = [];

    public function __construct(public $value)
    {
    }

    public function mapper(callable $callable): Collection
    {
        $Collection = new self($this->value);
        $Collection->items = array_map($callable, $this->value);

        return $Collection;
    }
}