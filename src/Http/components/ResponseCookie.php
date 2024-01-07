<?php

namespace Src\Http\Components;

use Src\Contracts\Http\Cookie;
use Stringable;

class ResponseCookie implements Cookie, Stringable
{
    /**
     * the name
     */
    private string $name;

    /**
     * the value
     */
    private string $value;

    /**
     * time of lifetime
     */
    private int $expires = 0;

    /**
     * path of action
     */
    private string $path = '/';

    /**
     * domain of action
     */
    private string $domain = '';

    /**
     * only works on secure protocols
     */
    private bool $secure = false;

    /**
     * only works for http(s) protocols
     */
    private bool $httpOnly = false;

    /**
     * build a instance of ResponseCookie
     */
    public function __construct(
        string $name,
        string $value = '',
        int $expires = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->expires = $expires;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        // 
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): string
    {
        // 
    }

    /**
     * string representation of cookie for response
     */
    public function toString(): string
    {
        $str = "Set-Cookie: {$this->name}={$this->value}";

        foreach ([
            'expires' => $this->expires,
            'path' => $this->path,
            'domain' => $this->domain,
            'httpOnly' => $this->httpOnly,
            'secure' => $this->secure,
        ] as $prop => $value) {
            $method = 'add' . ucfirst($prop) . 'OnString';
            $str = $this->$method($str, $value);
        }

        return $str;
    }

    private function addExpiresOnString(string $str, int $time): string
    {
        if ($time = 0) {
            return $str;
        }

        $time = time() + $time;

        return gmdate('', $time);
    }

    /**
     * string representation of cookie for response
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}