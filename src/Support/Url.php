<?php 

namespace Src\Support;

use Src\Support\Collection\Collection;

class Url
{
    /**
     * default port for url
     */
    public const DEFAULT_PORT = 80;

    /**
     * scheme using on url
     */
    private string $scheme;

    /**
     * domain of url
     */
    private string $host;

    /**
     * port of url
     */
    private string $port;

    /**
     * resources route
     */
    private string $uri;

    /**
     * query data
     */
    private array $querys;

    /**
     * make a url from string
     */
    public static function fromString(string $url): Url
    {
        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT) ?: '';
        $uri = parse_url($url, PHP_URL_PATH) ?: '';
        $scheme = parse_url($url, PHP_URL_SCHEME) ?: '';
        parse_str(parse_url($url, PHP_URL_QUERY) ?: '', $querys);

        return new Url($host, $port, $uri, $querys, $scheme);
    }

    /**
     * make a instance of class
     */
    public function __construct(string $host, string $port = '', string $uri = '/', array $querys = [], string $scheme = 'http')
    {
        $this->host = $host;
        $this->port = $port ?: $this::DEFAULT_PORT;
        $this->uri = $uri;
        $this->querys = $querys;
        $this->scheme = $scheme ?: 'http';
    }

    /**
     * get the host from url
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * get the port from url
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * get the uri from url
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * get the query string from url
     */
    public function getQuerys(): array
    {
        return $this->querys;
    }

    /**
     * get the query like a string
     */
    public function getStringQuery(): string
    {
        return http_build_query($this->querys);
    }

    /**
     * get the url like string
     */
    public function toString(): string
    {
        return sprintf(
            '%s://%s/%s%s',
            $this->scheme,
            $this->host,
            $this->uri,
            ($q = $this->getStringQuery()) ? '?' . $q : ''
        );
    }
}