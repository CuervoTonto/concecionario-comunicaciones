<?php

namespace Src\Routing;

use Closure;
use RuntimeException;
use Src\Container\Container;
use Src\Http\Request;

class Route
{
    /**
     * direction of the action
     */
    private string $url;

    /**
     * supported methods of request
     */
    private array $methods;

    /**
     * associate action
     */
    private Closure|array|string $action;

    /**
     * prefixes of route
     */
    private array $prefixes = [];

    /**
     * designed name
     */
    private ?string $name = null;

    /**
     * designed the middlewares
     * 
     * @var array<string>
     */
    private array $middlewares = [];

    /**
     * container instance
     */
    private Container $container;

    /**
     * build a instance of Route
     */
    public function __construct(
        string $url,
        array $methods,
        Closure|array|string $action,
        ?string $name = null,
        array $middlewares = [],
        Container $container = null,
    ) {
        $this->url = '/' . trim($url, '/');
        $this->methods = $methods;
        $this->action = $action;
        $this->name = $name;

        $this->setMiddlewares($middlewares);
        $this->setContainer($container ?: new Container());
    }

    /**
     * call the designed action
     */
    public function call(string $url): mixed
    {
        return $this->container->call(
            $this->action,
            $this->parametersOf($url),
        );
    }

    /**
     * get the url paremeters values
     * 
     * @param string $url the url to obtain parameters
     * 
     * @return array the extracted url parameters
     */
    public function parametersOf(string $url): array
    {
        $regex = $this->regexPrefixUrl();

        $match = preg_match("~^{$regex}$~", $url, $params);

        if (! $match) {
            throw new RuntimeException(
                "invalid url [{$url}]; expected [{$this->prefixUrl()}]"
            );
        }

        return array_slice($params, 1);
    }

    /**
     * check if route match with request
     */
    public function match(Request $request): bool
    {
        return $this->methodMatch($request->method())
            && $this->urlMatch($request->baseUri());
    }

    /**
     * check if route url match with other url
     */
    public function urlMatch(string $url): bool
    {
        return preg_match('~^' . $this->regexPrefixUrl() . '$~', $url);
    }

    /**
     * add prefix
     */
    public function prefix(string $prefix): static
    {
        if (! empty($prefix = trim($prefix, '/'))) {
            $this->prefixes[] = $prefix;
        }

        return $this;
    }

    /**
     * obtains the route prefix string
     */
    public function getPrefix(): string
    {
        return rtrim('/' . implode('/', $this->prefixes), '/');
    }

    /**
     * the prefixed url
     */
    public function prefixUrl(): string
    {
        return rtrim($this->getPrefix() . $this->url, '/') ?: '/';
    }

    /**
     * obtains url as regular expression (regex)
     */
    public function regexUrl(): string
    {
        return preg_replace('/{[^}]+}/', '([^/]+)', $this->url);
    }

    /**
     * obtains url as regular expression (regex)
     */
    public function regexPrefixUrl(): string
    {
        return preg_replace('/{[^}]+}/', '([^/]+)', $this->prefixUrl());
    }

    /**
     * check if some route method match a method
     */
    public function methodMatch(string $method): bool
    {
        return in_array($method, $this->methods, true);
    }

    /**
     * set the middlewares of the route
     * 
     * @todo
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = [];
        $this->middleware(...$middlewares);
    }

    /**
     * @todo
     */
    public function middleware(string ...$middlewares): static
    {
        foreach ($middlewares as $mdl) {
            $this->middlewares[] = $mdl;
        }

        return $this;
    }

    /**
     * set the container instance
     */
    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * get the route name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * get the route designed middlewares
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * get the route designed methods
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}