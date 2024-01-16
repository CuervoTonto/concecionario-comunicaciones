<?php

namespace Src\Database;

use Src\Classes\Globals;

class DBInitiator
{
    /**
     * prevents class instatiation
     */
    private function __construct() {}

    /**
     * initiate manager
     */
    public static function init(): void
    {
        if (Globals::exists('db.manager')) {
            return;
        }

        Globals::add('db.manager', new Manager(
            require fromBase('config/database.php'),
        ));
    }
}