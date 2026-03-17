<?php

use Src\Classes\Globals;

if (! function_exists('fromBase')) {
    /**
     * prepend app base path to a string
     * 
     * @param string $str main string
     * 
     * @return string the concatenated strirng
     */
    function fromBase(string $str): string
    {
        return rtrim(Globals::get('app_base') . "/{$str}", '/');
    }
}

if (! function_exists('fromViews')) {
    /**
     * prepend app base path to a string
     * 
     * @param string $str main string
     * 
     * @return string the concatenated strirng
     */
    function fromViews(string $str): string
    {
        return rtrim(fromBase("views/{$str}"), '/');
    }
}