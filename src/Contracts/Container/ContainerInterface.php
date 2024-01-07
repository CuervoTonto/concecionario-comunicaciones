<?php 

namespace Contracts\Container;

use Closure;

interface ContainerInterface
{
    /**
     * store instance on shareds
     */
    public function instance(string $abstract, object $instance): void;

    /**
     * resolve the given type
     */
    public function resolve(string $abstract, array $parameters = []): object;

    /**
     * make a instance of the given type
     */
    public function make(string $type, array $parameters = []): object;

    /**
     * resolve and call the given callable
     */
    public function call(Closure|array|string $callable, array $parameters = []): mixed;

    /**
     * add alias to shared instance
     */
    public function alias(string $abstract, string $alias): void;

    /**
     * get the abstract using alias
     */
    public function getAbstractByAlias(string $alias): string;
}