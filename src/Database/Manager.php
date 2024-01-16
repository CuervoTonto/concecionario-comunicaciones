<?php

namespace Src\Database;

use PDO;
use Closure;
use Src\Support\Configuration;

class Manager
{
    /**
     * list of connections
     * 
     * @var array<Connection>
     */
    private array $connections = [];

    /**
     * configuration
     */
    private Configuration $config;

    /**
     * connection options
     */
    private array $connOpts = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ];

    /**
     * build a instance of Manager
     * 
     * @param Configuration $conf configuration to manager
     */
    public function __construct(Configuration $conf)
    {
        $this->config = $conf;
    }

    /**
     * obtains a connection from manager
     * 
     * @param string $name connection's name
     * 
     * @return Connection
     */
    public function connection(string $name): Connection
    {
        return $this->connections[$name] ?? $this->createConnection($name);
    }

    /**
     * check if manager has a given connection
     * 
     * @param string $name name of the connection
     * 
     * @return bool connection was found
     */
    public function hasConnection(string $name): bool
    {
        return array_key_exists($name, $this->connections);
    }

    /**
     * add a connection to list
     * 
     * @param string $name the name of the connection
     * @param Connection $conn the connection instance
     * 
     * @return void
     */
    protected function addConnection(string $name, Connection $conn): void
    {
        $this->connections[$name] = $conn;
    }

    /**
     * create a instance of Connection
     * 
     * the connection would be added to list automatically
     * 
     * @param string $name name of the connection
     * 
     * @return Connection
     */
    protected function createConnection(string $name): Connection
    {
        return new Connection(
            $this->obtainPDOClosure()
        );
    }

    /**
     * obtains the closure to create a PDO connection instance
     * 
     * @return Closure
     */
    protected function obtainPDOClosure(): Closure
    {
        return function (): PDO {
            return new PDO(
                $this->makeDsnForPDO(),
                $this->config->get('user'),
                $this->config->get('password'),
                $this->connOpts,
            );
        };
    }

    /**
     * obtains the dsn string for pdo connections
     * 
     * @return string dsn string
     */
    private function makeDsnForPDO(): string
    {
        $config = $this->config;

        return array_reduce(
            array_keys($config->toArray()),
            /**
             * concatenate attributes to dsn string
             * 
             * @param string $dsn dsn string
             * @param string $attr the attribute to add
             * 
             * @return string concatenated dsn string
             */
            function (string $dsn, string $attr) use ($config): string {
                return $dsn . "{$attr}={$config->get($attr)};";
            },
            'mysql:'
        );
    }
}