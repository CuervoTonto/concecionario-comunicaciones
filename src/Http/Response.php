<?php

namespace Src\Http;

use Src\Contracts\Various\Renderable;
use Stringable;

class Response
{
    /**
     * status of the response
     */
    private int $status;

    /**
     * headers for the response
     */
    private array $headers;

    /**
     * headers cookies
     */
    private array $cookies;

    /**
     * response content
     */
    private string $content;

    /**
     * create a Response to redirect
     * 
     * @param string $url the url of redirection
     * @param array $headers others headers
     * @param array $cookies cookies
     * 
     * @return \Src\Http\Response
     */
    public static function redirect(
        string $url,
        array $headers = [],
        array $cookies = [],
    ): Response {
        $headers[] = ["Location: {$url}", true];

        return new Response("redirecting to: {$url}", 302, $headers, $cookies);
    }

    /**
     * build a instance of Response
     * 
     * @param Renderable|Stringable|string|null $content the response content
     * @param int $status the response status
     * @param array $headers the headers
     * @param array $cookies the headers cookies (the arguments to function "setcookie")
     */
    public function __construct(
        Renderable|Stringable|string|null $content = '',
        int $status = 200,
        array $headers = [],
        array $cookies = [],
    ) {
        $this->setContent($content);
        $this->setStatus($status);
        $this->setHeaders($headers);
        $this->setCookies($cookies);
    }

    /**
     * WARGNING; send apply the response to interal and end the script execution
     */
    public function burn(): void
    {
        exit($this->send());
    }

    /**
     * send the response to user
     * 
     * @return void
     */
    public function send(): void
    {
        $this->applyStatus();
        $this->applyCookies();
        $this->applyHeaders();
        $this->sendContent();
    }

    /**
     * apply the current status to internal response
     * 
     * @return void
     */
    public function applyStatus(): void
    {
        http_response_code($this->status);
    }

    /**
     * apply the current status to internal response
     * 
     * @return void
     */
    public function applyCookies(): void
    {
        foreach ($this->cookies as $c) setcookie(...$c);
    }

    /**
     * apply the headers to interal response
     * 
     * @return void
     */
    public function applyHeaders(): void
    {
        foreach ($this->headers as $h) header($h[0], $h[1]);
    }

    /**
     * print the response to "burn" the headers and status
     * 
     * @return void
     */
    public function sendContent(): void
    {
        echo $this->content;
    }

    /**
     * the the content of the response
     * 
     * @param Renderable|Stringable|string|null $content the new content
     * 
     * @return void
     */
    public function setContent(Renderable|Stringable|string|null $content): void
    {
        if ($content instanceof Renderable) {
            $content = $content->render();
        } elseif ($content instanceof Stringable) {
            $content = $content->__toString();
        }

        $this->content = (string) $content;
    }

    /**
     * set the code status 
     * 
     * @param int $status the new status value
     * 
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * set the headers of response
     * 
     * @param array<array> $headers array with new headers (parameters to addHeader)
     * 
     * @return void
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = [];

        foreach ($headers as $header) {
            $this->addHeader(...$header);
        }
    }

    /**
     * add header to response
     * 
     * @param string $header the header string
     * @param bool $replace indicate if replace others headers of same type
     * 
     * @return void
     */
    public function addHeader(string $header, bool $replace = false): void
    {
        $this->headers[] = [$header, $replace];
    }

    /**
     * set the cookies of the response
     * 
     * @param array<array> $cookies the cookies array
     * 
     * @return void
     */
    public function setCookies(array $cookies): void
    {
        $this->cookies = [];

        foreach ($cookies as $cookie) {
            $this->addCookie(...$cookie);
        }
    }

    /**
     * add cookie to response header
     */
    public function addCookie(
        string $name,
        string $value = '',
        int $expires = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    ): void {
        $this->cookies[] = [
            $name,
            $value,
            time() + $expires,
            $path,
            $domain,
            $secure,
            $httpOnly
        ];
    }

    /**
     * obtains the current headers to response
     * 
     * @return array<string> the current headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * obtains the current cookies to response
     * 
     * @return array<array> the array with cookies (arguments to "setcookie")
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * obtains the content of response
     * 
     * @return string the current content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * obtains the status code
     * 
     * @return int the current status code
     */
    public function status(): int
    {
        return $this->status;
    }
}