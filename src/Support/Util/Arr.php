<?php 

namespace Src\Support\Util;

use Generator;

final class Arr
{
    /**
     * prevents the instantiation of class
     */
    private function __construct() {}

    /**
     * make a array using extracted items from another
     * 
     * @param array $from array with the items to extract
     * @param array<string> $extract array with the keys of elements to extract
     * 
     * @return array<string, mixed> items extracteds
     */
    public static function extractItems(array $from, array $extract): array
    {
        return $filtered = array_filter($from, fn($k) => in_array($k, $extract));
    }

    /**
     * check if array contains a array on his elements
     */
    public static function isNested(array $array): bool
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                return true;
            }
        }

        return false;
    }
}