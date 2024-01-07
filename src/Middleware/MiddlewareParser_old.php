<?php 

namespace Src\Middleware;

class MiddlewareParser
{
    /**
     * middleware aliases
     */
    private array $aliases;

    /**
     * middleware groups
     */
    private array $groups;

    /**
     * make a instance of class
     */
    public function __construct(array $aliases = [], array $groups = [])
    {
        $this->aliases = [];
        $this->groups = [];

        $this->addAliasesList($aliases);
        $this->addGroupsList($groups);
    }

    /**
     * add array of aliases
     */
    private function addAliasesList(array $aliases): void
    {
        foreach ($aliases as $alias => $value) $this->addAlias($alias, $value);
    }

    /**
     * add array of groups
     */
    private function addGroupsList(array $groups): void
    {
        foreach ($groups as $group => $list) $this->addGroup($group, $list);
    }

    /**
     * add alias
     */
    private function addAlias(string $alias, string $middleware): void
    {
        $this->aliases[$alias] = $middleware;
    }

    /**
     * add group
     */
    private function addGroup(string $group, array $middlewares): void
    {
        $this->groups[$group] = $middlewares;
    }

    /**
     * get the middleware by the alias
     */
    public function getByAlias(string $alias): string
    {
        return $this->aliases[$alias] ?? $alias;
    }

    /**
     * parse middleware
     */
    public function parseMiddleware(string $definition): array
    {
        $parts = explode(':', $definition, 2);
        $parts[1] = $parts[1] ?? '';

        $middleware = $this->getByAlias($parts[0]);
        $parameters = [];

        if (str_contains($parts[1], ':')) {
            $parameters = explode(':', $parts[1]);
        } else {
            $parameters = explode(',', $parts[1]);
        }

        $parameters = array_filter($parameters, fn($v) => ! empty($v));

        return [$middleware, ...$parameters];
    }

    /**
     * parse a array of middleware
     */
    public function parseMiddlewareList(array $list): array
    {
        $middlewares = [];

        foreach ($list as $middleware) {
            $middlewares[] = $this->parseMiddleware($middleware);
        }

        return $middlewares;
    }

    /**
     * get a middleware groups
     */
    public function getGroup(string $group): array
    {
        return $this->groups[$group];
    }
}