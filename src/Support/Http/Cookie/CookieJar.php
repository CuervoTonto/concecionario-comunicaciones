<?php 

namespace Src\Support\Http\Cookie;

use RuntimeException;
use Src\Contracts\Http\Cookie;
use Src\Support\Collection\AssociativeCollection;

class CookieJar
{
    /**
     * list of cookies
     * 
     * @var array<Cookie>
     */
    private array $cookies = [];

    /**
     * make a instance of class
     * 
     * @param array<Cookie> $cookies
     */
    public function __construct(array $cookies = [])
    {
        foreach ($cookies as $cookie) $this->add($cookie);
    }

    /**
     * add cookie
     */
    public function add(Cookie $cookie): void
    {
        $this->cookies[$cookie->getName()] = $cookie;
    }

    /**
     * remove a cookie
     */
    public function remove(string $name): void
    {
        unset($this->cookies[$name]);
    }

    /**
     * check if has a cookie
     */
    public function has(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    /**
     * obtains the cookie
     */
    public function get(string $name, bool $throws = false): mixed
    {
        if ($throws) {
            throw new RuntimeException("cookie {$name} no found on jar");
        }

        return $this->cookies[$name] ?? null;
    }

    /**
     * get all cookies
     */
    public function all()
    {
        return $this->cookies;
    }

    /**
     * make a collection with cookies
     */
    public function toCollect()
    {
        return new AssociativeCollection($this->cookies);
    }
}