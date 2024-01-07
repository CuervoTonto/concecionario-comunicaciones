<?php

use Src\App\Controllers\SomeController;
use Src\Http\Request;
use Src\Http\Response;
use Src\Routing\Router;
use Src\Validator\Validator;
use Src\View\View;

/** @var Router */
$router = $this;

// $router->get('/', fn() => 'proof');

$router->get('/', fn() => '<a href="valid">validate data</a><br><br>' . print_r(session()->allErrors(), true));
$router->get('/valid', [SomeController::class, 'index']);
$router->get('errors', [SomeController::class, 'err']);
// $router->get('/', fn() => Response::redirect('home'));

/*
$router->group(['prefix' => 'assignatures'], function (Router $router) {
    $router->group(['prefix' => '{id}'], function (Router $router) {
        $router->get('show', fn(int $id) => "assig: #{$id}");
    });
});
*/

/*
$router->get('/', function () {
    return new View(fromBase('views/home.php'));
});
*/

// $router->fail(function () {
//     return new Response('Page not found', 404);
// });