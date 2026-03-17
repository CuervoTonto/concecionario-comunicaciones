<?php

use Src\Classes\Globals;
use Src\Container\Container;
use Src\Http\Request;
use Src\Session\Session;
use Src\Support\Url\UrlGenerator;


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

if (! function_exists('url')) {
    /**
     * obtains the UrlGenerator instance from RequestHandler
     * 
     * @return UrlGenerator
     */
    function url(): UrlGenerator
    {
        return container()->resolve('url');
    }
}