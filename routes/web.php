<?php

use Src\Routing\Router;

/** @var Router */
$router = $this;

$router->get('/', fn() => 'proof');