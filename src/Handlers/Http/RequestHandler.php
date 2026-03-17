<?php

namespace Src\Handlers\Http;

use Closure;
use Src\Classes\Globals;
use Src\Container\Container;
use Src\Http\Request;
use Src\Http\Response;
use Src\Middleware\MiddlewareParser;
use Src\Middleware\MiddlewarePipe;
use Src\Routing\Route;
use Src\Routing\Router;
use Src\Session\Session;
use Src\Session\SessionInitiator;
use Src\Support\Configuration;
use Src\Support\Url\UrlGenerator;

class RequestHandler
{
    /**
     * container instance
     */
    private Container $container;

    /**
     * resulting response
     */
    private ?Response $response = null;

    /**
     * actions to apply after get response on "handle()"
     */
    private array $callbacks = [];

    /**
     * build a instance of RequestHandler
     * 
     * @param \Src\Container\Container $container container instance
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container ?? new Container();
    }

    /**
     * handle the given request
     */
    public function handle(Request $request): Response
    {
        $this->container->instance('request', $request);
        $this->registerInstances();

        $res = $this->container()->resolve('router')->handle($request);

        if (! $res instanceof Response) {
            $res = new Response($res);
        }

        $this->callResponseActions($res);

        return $res;
    }

    /**
     * register the instance on container
     */
    private function registerInstances(): void
    {
        $this->container->instance('router', $this->createRouter());
        $this->container->instance('session', $this->createSession());
        $this->container->instance('url', $this->createUrlGenerator());
    }

    /**
     * create a router and configure router instance
     * 
     * @return \Src\Routing\Router router instance
     */
    private function createRouter(): Router
    {
        /** @var Configuration */
        $config = require_once(
            Globals::get('app_base') . '/config/middlewares.php'
        );

        $parser =  new MiddlewareParser(
            $config->get('aliases'),
            $config->get('groups'),
            $config->get('priority'),
        );

        $pipe =  new MiddlewarePipe($this->container, $parser);
        $router = new Router($pipe, $this->container);

        $router->group(
            ['middleware' => 'basic'],
            Globals::get('app_base') . '/routes/web.php',
        );

        return $router;
    } 

    /**
     * create a instance of Session
     */
    private function createSession(): Session
    {
        return (new SessionInitiator($this, $this->container))->run();
    }

    /**
     * create a instance of UrlGenerator with named routes
     */
    private function createUrlGenerator(): UrlGenerator
    {
        /** @var Router */
        $router = $this->container->resolve('router');

        return new UrlGenerator(array_map(function (Route $route) {
            return $route->prefixUrl();
        }, $router->allNamedRoutes()));
    }

    /**
     * add a action to response
     * 
     * @param Closure<Response>:Response $action the action
     * 
     * @return void
     */
    public function addResponseAction(Closure $action): void
    {
        if (array_search($action, $this->callbacks, true) === false) {
            $this->callbacks[] = $action;
        }
    }

    /**
     * call apply response callbacks to response
     * 
     * @param Response $res response instance
     * 
     * @return void
     */
    public function callResponseActions(Response $res): void
    {
        foreach ($this->callbacks as $c) $c($res);
    }

    /**
     * obtains the container instance
     */
    public function container(): Container
    {
        return $this->container;
    }
}