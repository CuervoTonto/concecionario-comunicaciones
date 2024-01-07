<?php

namespace Src\Session;

use App\Application;
use Src\Classes\Globals;
use Src\Support\Configuration;

class SessionConfig extends Configuration
{
    /**
     * construct a instance of SessionConfig
     */
    public function __construct()
    {
        parent::__construct([
            'repository' => [
                'save_path' => fromBase('resources/tmp/session'),
                'use_cookies' => false,
            ]
        ]);
    }
}