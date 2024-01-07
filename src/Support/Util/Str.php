<?php 

namespace Src\Support\Util;

final class Str
{
    /**
     * uninstantiable class
     */
    private function __construct() {}

    /**
     * check if a string is empty or is only made of whitespaces
     */
    public static function isBlank(string $string): bool
    {
        return empty(trim($string));;
    }
}