<?php

use Src\Routing\Router;
use Src\View\View;

/** @var Router */
$router = $this;

$router->get('/', function () {
    return new View(fromViews('working.php'));
});