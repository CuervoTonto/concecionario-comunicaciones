<?php

namespace Src\Http;

use RuntimeException;
use Src\Http\Components\Cookie;
use Src\Support\Bag\Bag;
use Src\Support\Bag\ServerBag;
use Src\Support\Http\Cookie\CookieJar;

class Request
{
    /**
     * query data
     */
    private Bag $query;

    /**
     * body parameters data
     */
    private Bag $body;

    /**
     * server data
     */
    private Bag $server;

    /**
     * headers data
     */
    private Bag $headers;

    /**
     * received cookies
     */
    private CookieJar $cookies;

    /**
     * request method
     */
    private string $method;

    /**
     * target url
     */
    private string $url;

    /**
     * target domain
     */
    private string $domain;

    /**
     * base of target url
     */
    private string $baseUrl;

    /**
     * referer url
     */
    private ?string $referer;

    /**
     * session associate
     */
    private ?string $session = null;

    /**
     * build a instance of Request
     */
    public function __construct(
        array $query = [],
        array $body = [],
        array $server = [],
        array $headers = [],
        array $cookies = [],
    ) {
        $this->query = new Bag($query);
        $this->body = new Bag($body);
        $this->server = new ServerBag($server);
        $this->headers = new Bag($headers);

        $this->cookies = new CookieJar(array_map(
            [$this, 'createCookie'],
            array_keys($cookies),
            $cookies,
        ));

        $this->initialize();
    }

    /**
     * initialize the request
     */
    private function initialize(): void
    {
        $this->method = $this->server('REQUEST_METHOD', 'GET');
        $this->referer = $this->server('HTTP_REFERER');
        $this->domain = $this->server('SCHEME') . '://' .$this->server('HTTP_HOST');
        $this->session = $this->cookie('SESSION_COOKIE')?->getValue();
        $this->url = $this->domain . $this->server('REQUEST_URI');
        $this->baseUrl = strtok($this->url, '?');
    }

    /**
     * create a instance of Cookie
     * 
     * @param string $value the value for the cookie
     * @param string $name the name for the cookie
     * 
     * @return \Src\Http\Components\Cookie
     */
    private function createCookie(string $name, string $value): Cookie
    {
        return new Cookie($name, $value);
    }

    /**
     * obtains a value from data
     * 
     * @return mixed the data's value
     */
    public function get(string $name): mixed
    {
        return $this->body->get($name)
            ?? $this->query->get($name)
            ?? throw new RuntimeException("undefined data {$name}");
    }

    /**
     * handle attempt of get a undefined property
     * 
     * @return mixed the data's value
     */
    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    /**
     * obtains the value of a data from query
     */
    public function query(string $name): mixed
    {
        return $this->query[$name];
    }

    /**
     * obtains the value of a data from body
     */
    public function body(string $name): mixed
    {
        return $this->body->get($name);
    }

    /**
     * obtains the value of a data from server
     */
    public function server(string $name): mixed
    {
        return $this->server->get($name);
    }

    /**
     * obtains a cookie from cookies
     */
    public function cookie(string $name): mixed
    {
        return $this->cookies->get($name);
    }

    /**
     * obtains the value of a data from headers 
     */
    public function header(string $name): mixed
    {
        return $this->headers->get($name);
    }

    /**
     * obtains the request method
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * obtains the target url
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * obtains the base of the target url
     */
    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * obtains the uri of the target url
     */
    public function uri(): string
    {
        return rtrim($this->server('REQUEST_URI'), '/') ?: '/';
    }

    /**
     * obtains the base uri of the target url
     */
    public function baseUri(): string
    {
        return rtrim(strtok($this->server('REQUEST_URI'), '?'), '/') ?: '/';
    }

    /**
     * obtains the referer url
     */
    public function referer(): ?string
    {
        return $this->referer;
    }

    /**
     * obtains the session identifier
     */
    public function session(): ?string
    {
        return $this->session;
    }

    /**
     * create a instance of request using the global vars
     * 
     * @return \Src\Http\Request
     */
    public static function fromGlobalsVars(): Request
    {
        return new static($_GET, $_POST, $_SERVER, getallheaders(), $_COOKIE);
    }
}