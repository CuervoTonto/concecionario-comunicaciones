<?php

namespace Src\Middleware;

class MiddlewareParser
{
    /**
     * middlewares aliases
     * 
     * @var array<string>
     */
    private array $aliases;

    /**
     * middlewares groups
     * 
     * @var array<string, array>
     */
    private array $groups;

    /**
     * middlewares priority
     * 
     * @var array<string>
     */
    private array $priority;

    /**
     * build a instance of MiddlewareParser
     */
    public function __construct(
        array $aliases = [],
        array $groups = [],
        array $priority = [],
    ) {
        $this->aliases = $aliases;
        $this->groups = $groups;
        $this->priority = $priority;
    }

    /**
     * obtains a middleware from the alias
     * 
     * @param string $alias alias of the middleware
     * 
     * @return string the middleware or (if no found) the given alias
     */
    public function byAlias(string $alias): string
    {
        return $this->aliases[$alias] ?? $alias;
    }

    /**
     * check if middleware parser has a given group
     * 
     * @param string $group the group name
     * 
     * @return bool indicate if has a group with the name
     */
    public function hasGroup(string $group): bool
    {
        return array_key_exists($group, $this->groups);
    }

    /**
     * obtains the a middleware group
     * 
     * @param string $group the group name
     * 
     * @return array<string> $group of middlewares
     */
    public function getGroup(string $group): array
    {
        return $this->groups[$group];
    }

    /**
     * transform string middleware to correct form
     * 
     * @param string $middleware middleware on stirng
     * 
     * @return array the parsed middleware
     */
    public function parse(string $middleware): array
    {
        if ($this->hasGroup($middleware)) {
            return $this->parseList($this->getGroup($middleware));
        }

        $parts = explode(':', $middleware, 2);

        return [
            'middleware' => $middleware,
            'class' => $this->byAlias($parts[0]),
            'parameters' => array_filter(
                explode(',', $parts[1] ?? ''),
                fn(string $item) => ! empty($item),
            ),
        ];
    }

    /**
     * parse a list of middlewares
     * 
     * @param array<string> $middlewares list of middlewares
     * 
     * @return array parsed list of middlewares
     */
    public function parseList(array $list): array
    {
        $newList = [];

        foreach ($list as $key => $item) {
            $parsed = $this->parse($item);

            if (! array_key_exists('class', $parsed)) {
                $newList = array_merge($newList, $parsed);
            } else {
                $newList[] = $parsed;
            }
        }

        return $newList;
    }

    /**
     * order a list of (parsed) middlewares by priority
     * 
     * @param array $list list of parsed middlewares
     * 
     * @return array ordered list of middlewares
     */
    public function order(array $list): array
    {
        usort($list, [$this, 'comparePriority']);

        return $list;
    }

    /**
     * compare the priority of a couple of middlewares
     * 
     * @param string $a string of middleware class or parsed middleware
     * @param string $b other string of middleware class or parsed middleware
     * 
     * @return int comparation result
     */
    private function comparePriority(array|string $a, array|string $b): int
    {
        if (is_array($a)) {
            $a = $a['class'];
        }

        if (is_array($b)) {
            $b = $b['class'];
        }

        return $this->getPriority($a) <=> $this->getPriority($b);
    }

    /**
     * obtains the priority of middleware
     * 
     * @param string $middleware string of middleware class
     * 
     * @return int priority
     */
    private function getPriority(string $middleware): int
    {
        $p = array_search($middleware, $this->priority);

        return $p === false ? -1 : $p;
    }
}