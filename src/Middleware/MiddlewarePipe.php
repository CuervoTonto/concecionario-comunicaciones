<?php

namespace Src\Middleware;

use Closure;
use RuntimeException;
use Src\Container\Container;

class MiddlewarePipe
{
    /**
     * middleware parser
     */
    private MiddlewareParser $parser;

    /**
     * container instance
     */
    private Container $container;

    /**
     * build a instance of MiddlewarePipe
     * 
     * @param array<string> $aliases alsises using for middlewares
     * @param array<string, array<string>> $groups groups of middlewares
     * @param Container $container container instance to build middleware instances
     */
    public function __construct(
        Container $container,
        MiddlewareParser $parser,
    ) {
        $this->container = $container;
        $this->parser = $parser;
    }

    /**
     * make a pipeline of middlewares
     * 
     * @param array<string> $middlewares list of middlewares
     * @param Closure $destination action after pass middlewares
     * 
     * @return Closure nested closures (pipeline)
     */
    public function make(array $middlewares, Closure $destination): Closure
    {
        $list = $this->parser->order(
            $this->parser->parseList($middlewares)
        );

        return array_reduce(
            $list,
            /**
             * nested a closure (middlewares) inside other
             * 
             * @param Closure $pipe pipeline
             * @param string $mdl middleware
             * 
             * @return Closure
             */
            function (Closure $pipe, array $mdl): Closure {
                return function (mixed $passable) use ($pipe, $mdl): mixed {
                    $instance = $this->container->resolve($mdl['class']);

                    if (! method_exists($instance, 'handle')) {
                        throw new RuntimeException(
                            "invalid [{$mdl['class']}] for middleware"
                        );
                    }

                    return $instance->handle(
                        $pipe,
                        $passable,
                        ...$mdl['parameters']
                    );
                };
            },
            $destination
        );
    }
}