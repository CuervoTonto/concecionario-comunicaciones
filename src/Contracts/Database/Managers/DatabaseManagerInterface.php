<?php 

namespace Contracts\Database\Managers;

use Src\Database\Connections\Connection;

interface DatabaseManagerInterface
{
    /**
     * make a instance of DatabaseManager
     */
    public function __construct(array $data);

    /**
     * get a connection of manager
     */
    public function connection(string $name): Connection;
}