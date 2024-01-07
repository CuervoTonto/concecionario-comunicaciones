<?php 

namespace Src\Support\Classes;

class File
{
    /**
     * uninstantiable class
     */
    private function __construct() {}

    /**
     * requiring a file
     * 
     * @param string $file file (with path) to load
     */
    public static function require(string $__file): mixed
    {
        return require $__file;
    }

    /**
     * requiring a file only once tiem
     * 
     * @param string $file file (with path) to load
     */
    public static function requireOnce(string $file): mixed
    {
        return require_once $file;
    }

    /**
     * include a file
     * 
     * @param string $file file (with path) to load
     */
    public static function include(string $file): mixed
    {
        return include $file;
    }

    /**
     * include a file only once tiem
     * 
     * @param string $file file (with path) to load
     */
    public static function includeOnce(string $file): mixed
    {
        return include_once $file;
    }
}