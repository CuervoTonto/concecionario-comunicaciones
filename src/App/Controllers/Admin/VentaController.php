<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Controller;
use Src\App\Models\Venta;
use Src\Http\Request;
use Src\Http\Response;
use Src\View\View;

class VentaController extends Controller
{
    public function index(): View
    {
        return new View(fromViews('admin/ventas/index.php'), [
            'ventas' => $this->queryWithRelations()->orderBy('venta_id', 'desc')->get(),
        ]);
    }

    public function create(): View
    {
        return new View(fromViews('admin/ventas/crear.php'));
    }

    public function store(Request $request): Response
    {
        $this->validate(
            data: [
                'cliente_id'   => $request->body('cliente_id'),
                'vehiculo_id'  => $request->body('vehiculo_id'),
                'fecha_venta'  => $request->body('fecha_venta'),
                'precio_final' => $request->body('precio_final'),
            ],
            validations: [
                'cliente_id'   => 'required',
                'vehiculo_id'  => 'required',
                'fecha_venta'  => 'required',
                'precio_final' => 'required',
            ],
            url: '/admin/ventas/crear'
        );

        Venta::create([
            'cliente_id'   => (int) $request->body('cliente_id'),
            'vehiculo_id'  => (int) $request->body('vehiculo_id'),
            'fecha_venta'  => $request->body('fecha_venta'),
            'precio_final' => (float) $request->body('precio_final'),
            'metodo_pago'  => $request->body('metodo_pago') ?: null,
        ]);

        session()->set('_flash_success', 'Venta registrada correctamente.');

        return Response::redirect('/admin/ventas');
    }

    public function show(int $id): View|Response
    {
        $venta = $this->queryWithRelations()
            ->where('Ventas.venta_id', '=', $id)
            ->first();

        if (is_null($venta)) {
            session()->set('_flash_success', 'Venta no encontrada.');
            return Response::redirect('/admin/ventas');
        }

        return new View(fromViews('admin/ventas/detalle.php'), [
            'venta' => $venta,
        ]);
    }

    public function edit(int $id): View|Response
    {
        $venta = Venta::where('venta_id', $id)->first();

        if (is_null($venta)) {
            session()->set('_flash_success', 'Venta no encontrada.');
            return Response::redirect('/admin/ventas');
        }

        return new View(fromViews('admin/ventas/editar.php'), [
            'venta' => $venta,
        ]);
    }

    public function update(Request $request, int $id): Response
    {
        $venta = Venta::where('venta_id', $id)->first();

        if (is_null($venta)) {
            return Response::redirect('/admin/ventas');
        }

        $this->validate(
            data: [
                'cliente_id'   => $request->body('cliente_id'),
                'vehiculo_id'  => $request->body('vehiculo_id'),
                'fecha_venta'  => $request->body('fecha_venta'),
                'precio_final' => $request->body('precio_final'),
            ],
            validations: [
                'cliente_id'   => 'required',
                'vehiculo_id'  => 'required',
                'fecha_venta'  => 'required',
                'precio_final' => 'required',
            ],
            url: "/admin/ventas/{$id}/editar"
        );

        $venta->update([
            'cliente_id'   => (int) $request->body('cliente_id'),
            'vehiculo_id'  => (int) $request->body('vehiculo_id'),
            'fecha_venta'  => $request->body('fecha_venta'),
            'precio_final' => (float) $request->body('precio_final'),
            'metodo_pago'  => $request->body('metodo_pago') ?: null,
        ]);

        session()->set('_flash_success', 'Venta actualizada correctamente.');

        return Response::redirect('/admin/ventas');
    }

    public function destroy(int $id): Response
    {
        $venta = Venta::where('venta_id', $id)->first();

        if (!is_null($venta)) {
            $venta->delete();
            session()->set('_flash_success', 'Venta eliminada correctamente.');
        }

        return Response::redirect('/admin/ventas');
    }

    // --- helpers ---

    private function queryWithRelations()
    {
        return Venta::query()
            ->from('Ventas')
            ->select(
                'Ventas.*',
                'Clientes.nombre', 'Clientes.apellido',
                'Clientes.documento_identidad', 'Clientes.telefono', 'Clientes.email',
                'Modelos.marca', 'Modelos.modelo as nombre_modelo',
                'Vehiculos.anio', 'Vehiculos.color', 'Vehiculos.numero_serie', 'Vehiculos.precio as precio_vehiculo'
            )
            ->innerJoin('Clientes', 'Ventas.cliente_id', '=', 'Clientes.cliente_id')
            ->innerJoin('Vehiculos', 'Ventas.vehiculo_id', '=', 'Vehiculos.vehiculo_id')
            ->innerJoin('Modelos', 'Vehiculos.modelo_id', '=', 'Modelos.modelo_id');
    }
}
