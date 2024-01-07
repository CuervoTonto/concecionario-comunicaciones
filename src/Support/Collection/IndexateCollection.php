<?php 

namespace Src\Support\Collection;

class IndexateCollection extends BaseCollection
{
    /**
     * make a instance of class
     */
    public function __construct(array $items)
    {
        foreach ($items as $key => $value) $this->add($value);
    }

    /**
     * add a item on collection
     */
    public function add(mixed $value): void
    {
        parent::setItems(parent::getItems() + [$value]);
    }

    /**
     * remove a item from collection
     */
    public function remove(string $key): void
    {
        parent::setItems(
            array_diff_key(parent::getItems(), [$key => ''])
        );
    }

    /**
     * get array of items
     */
    public function toArray(): array
    {
        return parent::getItems();
    }
}