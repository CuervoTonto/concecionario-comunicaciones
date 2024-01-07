<?php

use Src\Classes\Globals;
use Src\Container\Container;
use Src\Http\Request;
use Src\Session\Session;

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

if (! function_exists('container')) {
    /**
     * obtains the container instance from RequestHandler
     * 
     * @return \Src\Container\Container
     */
    function container(): Container
    {
        return Globals::get('RequestHandler')->container();
    }
}

if (! function_exists('session')) {
    /**
     * obtains the session instance from RequestHandler
     * 
     * @return \Src\Session\Session
     */
    function session(): Session
    {
        return container()->resolve('session');
    }
}

if (! function_exists('request')) {
    /**
     * obtains the request instance from RequestHandler
     * 
     * @return \Src\Http\Request
     */
    function request(): Request
    {
        return container()->resolve('request');
    }
}