<?php

namespace Src\Support\Collection;

use Closure;

use Src\Contracts\Collection\CollectionInterface;

class BaseCollection implements CollectionInterface
{
    /**
     * collection items
     */
    private array $items = [];

    /**
     * set the items of the collection
     */
    protected function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * get items on array
     */
    protected function getItems(): array
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key): mixed
    {
        return $this->items[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function contains(mixed $item): bool
    {
        return in_array($item, $this->items, true);
    }

    /**
     * {@inheritDoc}
     */
    public function keyOf(mixed $item): int|string|bool
    {
        return array_search($item, $this->items, true);
    }

    /**
     * {@inheritDoc}
     */
    public function map(callable $callable): static
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            $result[$key] = call_user_func($callable, $value, $key);
        }
        
        return new static($result);
    }

    /**
     * {@inheritDoc}
     */
    public function filter(callable $callable): static 
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            if (call_user_func($callable, $value, $key)) {
                $result[$key] = $value;
            }
        }
        
        return new static($result);
    }

    /**
     * {@inheritDoc}
     */
    public function iterate(Closure $callback): void
    {
        foreach ($this->items as $key => $value) {
            $callback($value, $key);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function sort(callable $callable): static 
    {
        $items = $this->items;
        usort($items, $callable);

        return new static($items);
    }

    /**
     * {@inheritDoc}
     */
    public function implode(string $glue = ''): string
    {
        return implode($glue, $this->filter(fn($value) => is_string($value))->getItems());
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->items);
    }
}