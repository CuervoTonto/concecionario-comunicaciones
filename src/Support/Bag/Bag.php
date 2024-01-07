<?php

namespace Src\Support\Bag;

use ArrayAccess;

class Bag implements ArrayAccess
{
    /**
     * bag items
     */
    private array $items = [];

    /**
     * make a instance of class
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item => $value) $this->set($item, $value);
    }

    /**
     * add a item to bag
     */
    public function set(int|string $key, mixed $value): void
    {
        $this->offsetSet($key, $value);
    }

    /**
     * get a item from bag
     */
    public function get(int|string $key_or_index, mixed $default = null): mixed
    {
        return $this->offsetGet($key_or_index) ?? $default;
    }

    /**
     * check if bag has a item
     */
    public function has(int|string $key_or_index): bool
    {
        return $this->offsetExists($key_or_index);
    }

    /**
     * remove a item from bag
     */
    public function remove(string $key_or_index): void
    {
        $this->offsetUnset($key_or_index);
    }

    /**
     * add a item
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * get a item from bag
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * check if a item exists
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * remove a item
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * get items of bag on array
     */
    public function all(): array
    {
        return $this->items;
    }
}