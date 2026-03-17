<?php

namespace Src\App\Controllers;

use Src\App\Models\Cliente;
use Src\App\Models\Modelo;
use Src\App\Models\Vehiculo;
use Src\View\View;

class HomeController
{
    public function index(): View
    {
        $vehiculos = Vehiculo::query()
            ->select('Vehiculos.*', 'Modelos.marca', 'Modelos.modelo')
            ->innerJoin('Modelos', 'Vehiculos.modelo_id', '=', 'Modelos.modelo_id')
            ->orderBy('vehiculo_id', 'desc')
            ->get();

        $totalVehiculos = count($vehiculos);
        $totalClientes  = count(Cliente::get());
        $totalMarcas    = count(Modelo::get());

        return new View(fromViews('home.php'), [
            'vehiculos'      => $vehiculos,
            'totalVehiculos' => $totalVehiculos,
            'totalClientes'  => $totalClientes,
            'totalMarcas'    => $totalMarcas,
        ]);
    }
}
