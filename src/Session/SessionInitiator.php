<?php 

namespace Src\Session;

use App\Application;
use Src\Container\Container;
use Src\Handlers\Http\RequestHandler;
use Src\Http\Request;
use Src\Http\Response;
use Src\Http\ResponseCookie;

class SessionInitiator
{
    /**
     * container instance
     */
    private Container $container;

    /**
     * Request handler instance
     */
    private RequestHandler $handler;

    /**
     * make a instance of class
     * 
     * @param Container $container container instacen
     */
    public function __construct(RequestHandler $handler, Container $container)
    {
        $this->handler = $handler;
        $this->container = $container;
    }

    /**
     * make a session instance corresponding the request
     */
    protected function makeSession()
    {
        /** @var Request */
        $request = $this->container->resolve('request');
        /** @var ?string */
        $id = $request->session();

        if (is_null($id)) {
            $this->handlerCreateCookie($id = session_create_id());
        }

        $this->handlerSaveSession($session = new Session($id));

        return $session;
    }

    /**
     * create session cookie on handled response
     * 
     * @param string $id session id used on cookie value
     * 
     * @return void
     */
    private function handlerCreateCookie(string $id): void
    {
        $action = function (Response $res) use ($id) {
            $res->addCookie('SESSION_COOKIE', $id, 60 * 60 * 24);
        };

        $this->handler->addResponseAction($action);
    }

    /**
     * save session data after the request hanled
     * 
     * @param Session $session the instance of session
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
     * initiate a session instance
     */
    public function run(): Session
    {
        return $this->makeSession();
    }
}