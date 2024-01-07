<?php 

namespace Src\Support\Bag;

class ServerBag extends Bag
{
    /**
     * make a instance of class
     */
    public function __construct(array $server = [])
    {
        parent::__construct($this->prepare($server));
    }

    /**
     * prepare array with info server
     */
    private function prepare(array $server): array
    {
        return array_replace([
            'SCHEME' => 'http',
            'REQUEST_URI' => '/',
            'REQUEST_METHOD' => 'GET',
            'SERVER_NAME' => 'localhost',
            'HTTP_HOST' => 'localhost',
            'REMOTE_ADDR' => '127.0.0.1',
            'SERVER_PORT' => '80',
            'REQUEST_TIME' => time(),
            'REQUEST_TIME_FLOAT' => microtime(true)
        ], $server);
    }
}