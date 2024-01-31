<?php

namespace Src\Session;
use Src\Support\Configuration;

class SessionConfiguration extends Configuration
{
    public function __construct()
    {
        parent::__construct([
            'repository.path' => fromBase('resources/tmp/session'),
            'repository.prefix' => 'session_',
            'cookie.name' => 'SESSION_COOKIE',
            'cookie.lifetime' => 3600 * 24 * 365 * 2,
            'token.lifetime' => 300,
        ]);
    }
}