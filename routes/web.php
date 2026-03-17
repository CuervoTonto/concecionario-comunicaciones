<?php

use Src\App\Controllers\HomeController;
use Src\App\Controllers\Admin\ClienteController;
use Src\App\Controllers\Admin\DashboardController;
use Src\App\Controllers\Admin\VehiculoController;
use Src\App\Controllers\Admin\VentaController;
use Src\Http\Response;
use Src\Routing\Router;
use Src\View\View;

/** @var Router */
$router = $this;

$router->get('/', [HomeController::class, 'index']);

$router->get('/admin', [DashboardController::class, 'index']);

// --- Clientes ---
$router->get('/admin/clientes',                [ClienteController::class, 'index']);
$router->get('/admin/clientes/crear',          [ClienteController::class, 'create']);
$router->post('/admin/clientes',               [ClienteController::class, 'store']);
$router->get('/admin/clientes/{id}/editar',    [ClienteController::class, 'edit']);
$router->post('/admin/clientes/{id}',          [ClienteController::class, 'update']);
$router->post('/admin/clientes/{id}/eliminar', [ClienteController::class, 'destroy']);

// --- Vehículos ---
$router->get('/admin/vehiculos',                [VehiculoController::class, 'index']);
$router->get('/admin/vehiculos/crear',          [VehiculoController::class, 'create']);
$router->post('/admin/vehiculos',               [VehiculoController::class, 'store']);
$router->get('/admin/vehiculos/{id}/editar',    [VehiculoController::class, 'edit']);
$router->post('/admin/vehiculos/{id}',          [VehiculoController::class, 'update']);
$router->post('/admin/vehiculos/{id}/eliminar', [VehiculoController::class, 'destroy']);

// --- Ventas ---
$router->get('/admin/ventas',                [VentaController::class, 'index']);
$router->get('/admin/ventas/crear',          [VentaController::class, 'create']);
$router->post('/admin/ventas',               [VentaController::class, 'store']);
$router->get('/admin/ventas/{id}',           [VentaController::class, 'show']);
$router->get('/admin/ventas/{id}/editar',    [VentaController::class, 'edit']);
$router->post('/admin/ventas/{id}',          [VentaController::class, 'update']);
$router->post('/admin/ventas/{id}/eliminar', [VentaController::class, 'destroy']);
