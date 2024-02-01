<?php

namespace Src\Session;

use Src\Container\Container;
use Src\Handlers\Http\RequestHandler;
use Src\Http\Response;
use Src\Support\Configuration;

class SessionInitiator
{
    /**
     * request handler instance
     */
    private RequestHandler $handler;

    /**
     * container instance
     */
    private Container $container;

    /**
     * configuration to sessions
     */
    private Configuration $configuration;

    /**
     * build a instance of SessionInitiator
     */
    public function __construct(RequestHandler $handler, Container $container)
    {
        $this->handler = $handler;
        $this->container = $container;
        $this->configuration = new SessionConfiguration();
    }

    /**
     * creates a session instance
     * 
     * @return Session
     */
    public function makeSession(): Session
    {
        /** @var \Src\Http\Request */
        $request = $this->container->resolve('request');
        $cookieName = $this->configuration->get('cookie.name');

        $session = Session::new(
            $id = $request->cookie($cookieName)?->getValue(),
            $this->configuration->toArray(),
        );

        if (is_null($id)) {
            $this->handlerSetCookie($cookieName, $session->id());
        }

        $this->handlerSaveSession($session);

        return $session;
    }

    /**
     * add action to set session cookie on handler
     * 
     * @param string $cookie cookie's name
     * @param string $value cookie's value
     * 
     * @return void 
     */
    private function handlerSetCookie(string $name, string $value): void
    {
        $action = function (Response $response) use ($name, $value) {
            $response->addCookie($name, $value, 86400 * 365);
        };

        $this->handler->addResponseAction($action);
    }

    /**
     * add action on handler to save session
     * 
     * @param Session $session session to save
     * 
     * @return void
     */
    private function handlerSaveSession(Session $session): void
    {
        $action = function () use ($session) {
            $session->save();
        };

        $this->handler->addResponseAction($action);
    }

    /**
     * run initiator
     */
    public function run(): Session
    {
        return $this->makeSession();
    }
}