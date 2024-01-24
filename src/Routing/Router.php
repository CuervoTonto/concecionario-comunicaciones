<?php

namespace Src\Routing;

use Closure;
use RuntimeException;
use Src\Classes\Globals;
use Src\Container\Container;
use Src\Http\Request;
use Src\Http\Response;
use Src\Middleware\MiddlewarePipe;
use Src\View\View;

class Router
{
    /**
     * list of routes
     * 
     * @var array<\Src\Routing\Route>
     */
    private array $routes;

    /**
     * association of names with the routes
     * 
     * @var array<string, \Src\Routing\Route>
     */
    private array $nameRoutes;

    /**
     * middlewares to design on routes
     * 
     * @var array<string, string>
     */
    private array $middlewares = [];

    /**
     * stack of attributes of group
     */
    private array $groupStack = [];

    /**
     * the pipe instance for middlewares
     */
    private MiddlewarePipe $pipe;

    /**
     * action of case of fail
     */
    private Closure $fail;

    /**
     * container instance
     */
    private Container $container;

    /**
     * build a instance of Router
     * 
     * @param null|Container $container the container instance
     * @param MiddlewarePipe $pipe the pipe maker for middlewares
     */
    public function __construct(MiddlewarePipe $pipe, ?Container $container = null)
    {
        $this->container = $container ?: new Container();
        $this->pipe = $pipe;
    }

    /**
     * add a route to list
     */
    public function addRoute(Route $route): Route
    {
        foreach ($route->getMethods() as $m) {
            $this->routes[$m][] = $route;
        }

        if (! is_null($route->getName())) {
            $this->nameRoutes[$route->getName()] = $route;
        }

        return $route;
    }

    /**
     * add group stack attributes to route
     */
    private function addAttributes(Route $route): Route
    {
        foreach ($this->groupStack as $group) {
            foreach ($group as $attr => $value) {
                $this->addAttributeToRoute($route, $attr, $value);
            }
        }

        return $route;
    }

    /**
     * add a attribute to route
     * 
     * @param Route  $route route instance
     * @param string $attr  name of attribute
     * @param mixed  $value attribute value
     * 
     * @return Route the route instance
     */
    private function addAttributeToRoute(
        Route $route,
        string $attr,
        mixed $value,
    ): Route {
        if (! method_exists($route, $attr)) {
            throw new RuntimeException("invalid route attribute [{$attr}]");
        }

        if (is_array($value)) {
            $route->$attr(...$value);
        } else {
            $route->$attr($value);
        }

        return $route;
    }
    
    /**
     * create a instance of Route
     */
    public function createRoute(
        string $url,
        array $methods,
        Closure|array|string $action,
        ?string $name = null,
    ): Route {
        $route = new Route(
            rtrim($url, '/') ?: '/',
            $methods,
            $action,
            $name,
            $this->middlewares,
            $this->container
        );

        $this->addAttributes($route);

        return $route;
    }

    /**
     * add a new route to GET method handle
     */
    public function get(
        string $url,
        Closure|array|string $action,
        ?string $name = null
    ): Route {
        return $this->addRoute(
            $this->createRoute($url, ['GET'], $action, $name)
        );
    }

    /**
     * add a new route to POST method handle
     */
    public function post(
        string $url,
        Closure|array|string $action,
        ?string $name = null
    ) {
        return $this->addRoute(
            $this->createRoute($url, ['POST'], $action, $name)
        );
    }

    /**
     * add a new route to POST method handle
     */
    public function both(
        string $url,
        Closure|array|string $action,
        ?string $name = null
    ) {
        return $this->addRoute(
            $this->createRoute($url, ['GET', 'POST'], $action, $name)
        );
    }

    /**
     * create a group of route with shared attributes
     * 
     * @param array $attributes the shared attributes
     * @param Closure|string $routes callback that defines the routes o file
     * path that contains the routes definition
     * 
     * @return void
     */
    public function group(array $attributes, Closure|string $routes): void
    {
        $this->groupStack[] = $attributes;

        $this->loadRoutes($routes);

        array_pop($this->groupStack);
    }

    /**
     * load the given routes
     * 
     * @param Closure|string $routes callback that defines the routes o file
     * path that contains the routes definition
     * 
     * @return void
     */
    private function loadRoutes(Closure|string $routes): void
    {
        is_string($routes) ? require_once($routes) : $routes($this);
    }

    /**
     * handle a request
     */
    public function handle(Request $request): mixed
    {
        $route = $this->match($request);

        $res = is_null($route)
            ? $this->obtainFail()
            : $this->callRoute($route, $request);

        return $res;
    }

    /**
     * call a route using middlewares
     * 
     * @param Route $route route instance
     * @param Request $request request instance
     * 
     * @return mixed
     */
    private function callRoute(Route $route, Request $request): mixed
    {
        $middlewares = $route->getMiddlewares();
        $destination = fn() => $route->call($request->baseUri());

        $pipe = $this->pipe->make($middlewares, $destination);

        return $pipe($request);
    }

    /**
     * find the route that match with request
     */
    public function match(Request $request): ?Route
    {
        $routes = $this->routes[$request->method()] ?? [];

        foreach ($routes as $route) {
            if ($route->match($request)) {
                return $route;
            }
        }
        
        return null;
    }

    /**
     * obtains a route using the name
     */
    public function byName(string $name): ?Route
    {
        return $this->nameRoutes[$name] ?? null;
    }

    /**
     * set response on case of not found
     */
    public function fail(Closure $action): void
    {
        $this->fail = $action;
    }

    /**
     * the default action on fail
     */
    public function defaultFailAction(): mixed
    {
        return function () {
            return new View(fromViews('404.php'));
        };
    }

    /**
     * obtains the fail action result
     */
    public function obtainFail(): mixed
    {
        return $this->container->call(
            $this->fail ?? $this->defaultFailAction(),
        );
    }
}