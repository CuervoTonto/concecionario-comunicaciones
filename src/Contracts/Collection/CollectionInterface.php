<?php 

namespace Src\Contracts\Collection;

use Closure;

interface CollectionInterface
{
    /**
     * get a item from collection
     */
    public function get(string $key): mixed;

    /**
     * check if collection contains a item
     */
    public function contains(mixed $item): bool;

    /**
     * get the key/index of the given item
     */
    public function keyOf(mixed $item): int|string|bool;

    /**
     * transform items
     */
    public function map(callable $callable): static;

    /**
     * filter the items
     */
    public function filter(callable $callable): static;

    /**
     * apply a callback on items (change no are applied)
     */
    public function iterate(Closure $callback): void;

    /**
     * sort items using a given function
     */
    public function sort(callable $callable): static;

    /**
     * join collection items with string
     */
    public function implode(string $glue = ''): string;

    /**
     * get the amount of items
     */
    public function count(): int;
}