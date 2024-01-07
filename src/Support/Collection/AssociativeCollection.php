<?php

namespace Src\Support\Collection;

class AssociativeCollection extends BaseCollection
{
    /**
     * make a instance of class
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) $this->set($key, $value);
    }

    /**
     * add a item on collection
     */
    public function set(string $key, mixed $value): void
    {
        parent::setItems(array_merge(parent::getItems(), [$key => $value]));
    }

    /**
     * remove a item from collection
     */
    public function remove(string $key): void
    {
        $items = parent::getItems();
        unset($items[$key]);
        parent::setItems($items);
    }

    /**
     * get array of items
     */
    public function toArray(): array
    {
        return parent::getItems();
    }
}