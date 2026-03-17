<?php

use Src\Classes\Globals;
use Src\Exceptions\ResponseException;
use Src\Handlers\Http\RequestHandler;
use Src\Http\Request;

// define('APP_START', microtime(true));

require_once __DIR__ . '/../src/autoload.php';
require_once __DIR__ . '/../src/Helpers/path.helper.php';
require_once __DIR__ . '/../src/Helpers/helper.php';

Globals::add('app_base', dirname(__DIR__));
Globals::add('RequestHandler', $handler = new RequestHandler());

try {
    $handler->handle(Request::fromGlobalsVars())->send();
} catch (ResponseException $e) {
    $e->response()->send();
}

// echo 'Time: ' . PHP_EOL;
// echo number_format(microtime(true) - APP_START, 10);