<?php

use Src\Http\Response;
use Src\Routing\Router;
use Src\View\View;

/** @var Router */
$router = $this;

$router->get('/', function () {
    return new View(fromViews('working.php'));
});

$router->get('/hola', function () {
    return "hoal";
});