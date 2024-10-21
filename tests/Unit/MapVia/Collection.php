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
        if(isset($this->value->items)){
            $Collection->items = array_map($callable, (array)$this->value->items);

            return $Collection;
        }
        $Collection->items = array_map($callable, $this->value ?? []);

        return $Collection;
    }
}