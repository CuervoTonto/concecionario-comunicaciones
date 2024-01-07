<?php

namespace Src\Classes;

use RuntimeException;

final class Globals
{
    /**
     * global values
     */
    private static array $values = [];

    /**
     * prevents instantiation
     */
    private function __construct() {}

    /**
     * add a value to globals
     */
    public static function add(string $identifier, mixed $value): void
    {
        self::$values[$identifier] = $value;
    }

    /**
     * check if has a value associate with identifier
     */
    public static function exists(string $identifier): bool
    {
        return array_key_exists($identifier, self::$values);
    }

    /**
     * get a value from globals
     * 
     * @param string $identifier the identifier of value
     * @param bool $throws indicate if must throw a exception if value not found
     * 
     * @return mixed the value associate with the identifier
     */
    public static function get(string $identifier, bool $throws = false): mixed
    {
        if (! self::exists($identifier) && $throws) {
            throw new RuntimeException("No global value for: {$identifier}");
        }

        return self::$values[$identifier] ?? null;
    }

    /**
     * get the array of globals
     */
    public static function asArray(): array
    {
        return self::$values;
    }
}