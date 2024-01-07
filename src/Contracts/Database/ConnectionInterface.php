<?php 

namespace Contracts\Database;

use Contracts\Database\Managers\DatabaseManagerInterface;

interface ConnectionInterface
{
    public function query();
}