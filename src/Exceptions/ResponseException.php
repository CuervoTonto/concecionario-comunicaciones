<?php

namespace Src\Exceptions;

use Exception;
use Src\Http\Response;

class ResponseException extends Exception
{
    /**
     * response instance
     */
    private Response $response;

    /**
     * build a instance of ResponseException
     * 
     * @param Response $res response instance
     * @param string $message message for exception
     */
    public function __construct(Response $res, string $message = '')
    {
        parent::__construct($message);
        $this->response = $res;
    }

    /**
     * obtains the response instance
     */
    public function response(): Response
    {
        return $this->response;
    }
}