<?php 

namespace Src\Support;

use ArrayAccess;
use Src\Support\Classes\File;
use Src\Support\Collection\AssociativeCollection;

class Configuration extends AssociativeCollection implements ArrayAccess
{
    /**
     * make a configuration from file
     */
    public static function fromFile(string $file): Configuration
    {
        return new Configuration(File::require($file));
    }

    /**
     * make a instance of class
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    /**
     * check if a items exists on configuration
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, parent::getItems());
    }

    /**
     * remove a items from configuration
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    /**
     * set item on configuration
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * get a item from configuration
     */
    public function offsetGet(mixed $offset): mixed
    {
        $this->get($offset);
    }
}