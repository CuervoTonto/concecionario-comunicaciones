<?php

namespace Src\App\Controllers\Admin;

use Src\App\Models\Cliente;
use Src\App\Models\Venta;
use Src\App\Models\Vehiculo;
use Src\View\View;

class DashboardController
{
    public function index(): View
    {
        $totalClientes  = count(Cliente::get());
        $totalVehiculos = count(Vehiculo::get());

        $ventas         = Venta::get();
        $totalVentas    = count($ventas);
        $ingresos       = array_sum(array_map(fn($v) => $v->precio_final, $ventas));

        // Ventas del mes actual
        $mesActual = date('Y-m');
        $ventasMes = array_filter($ventas, fn($v) => str_starts_with($v->fecha_venta, $mesActual));
        $ingresosMes = array_sum(array_map(fn($v) => $v->precio_final, $ventasMes));

        return new View(fromViews('admin/dashboard.php'), [
            'totalClientes'  => $totalClientes,
            'totalVehiculos' => $totalVehiculos,
            'totalVentas'    => $totalVentas,
            'ingresos'       => $ingresos,
            'ventasMes'      => count($ventasMes),
            'ingresosMes'    => $ingresosMes,
        ]);
    }
}
