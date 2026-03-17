<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Controller;
use Src\App\Models\Modelo;
use Src\App\Models\Vehiculo;
use Src\Http\Request;
use Src\Http\Response;
use Src\View\View;

class VehiculoController extends Controller
{
    public function index(): View
    {
        return new View(fromViews('admin/vehiculos/index.php'), [
            'vehiculos' => $this->queryWithModelo()->orderBy('vehiculo_id', 'desc')->get(),
        ]);
    }

    public function create(): View
    {
        return new View(fromViews('admin/vehiculos/crear.php'));
    }

    public function store(Request $request): Response
    {
        $this->validate(
            data: [
                'marca'        => $request->body('marca'),
                'modelo'       => $request->body('modelo'),
                'anio'         => $request->body('anio'),
                'numero_serie' => $request->body('numero_serie'),
                'precio'       => $request->body('precio'),
            ],
            validations: [
                'marca'        => 'required',
                'modelo'       => 'required',
                'anio'         => 'required',
                'numero_serie' => 'required',
                'precio'       => 'required',
            ],
            url: '/admin/vehiculos/crear'
        );

        $modeloReg = $this->findOrCreateModelo(
            $request->body('marca'),
            $request->body('modelo')
        );

        Vehiculo::create([
            'modelo_id'    => $modeloReg->modelo_id,
            'anio'         => (int) $request->body('anio'),
            'color'        => $request->body('color') ?: null,
            'numero_serie' => $request->body('numero_serie'),
            'precio'       => (float) $request->body('precio'),
        ]);

        session()->set('_flash_success', 'Vehículo creado correctamente.');

        return Response::redirect('/admin/vehiculos');
    }

    public function edit(int $id): View|Response
    {
        $vehiculo = $this->queryWithModelo()
            ->where('Vehiculos.vehiculo_id', '=', $id)
            ->first();

        if (is_null($vehiculo)) {
            session()->set('_flash_success', 'Vehículo no encontrado.');
            return Response::redirect('/admin/vehiculos');
        }

        return new View(fromViews('admin/vehiculos/editar.php'), [
            'vehiculo' => $vehiculo,
        ]);
    }

    public function update(Request $request, int $id): Response
    {
        $vehiculo = Vehiculo::where('vehiculo_id', $id)->first();

        if (is_null($vehiculo)) {
            return Response::redirect('/admin/vehiculos');
        }

        $this->validate(
            data: [
                'marca'        => $request->body('marca'),
                'modelo'       => $request->body('modelo'),
                'anio'         => $request->body('anio'),
                'numero_serie' => $request->body('numero_serie'),
                'precio'       => $request->body('precio'),
            ],
            validations: [
                'marca'        => 'required',
                'modelo'       => 'required',
                'anio'         => 'required',
                'numero_serie' => 'required',
                'precio'       => 'required',
            ],
            url: "/admin/vehiculos/{$id}/editar"
        );

        $modeloReg = $this->findOrCreateModelo(
            $request->body('marca'),
            $request->body('modelo')
        );

        $vehiculo->update([
            'modelo_id'    => $modeloReg->modelo_id,
            'anio'         => (int) $request->body('anio'),
            'color'        => $request->body('color') ?: null,
            'numero_serie' => $request->body('numero_serie'),
            'precio'       => (float) $request->body('precio'),
        ]);

        session()->set('_flash_success', 'Vehículo actualizado correctamente.');

        return Response::redirect('/admin/vehiculos');
    }

    public function destroy(int $id): Response
    {
        $vehiculo = Vehiculo::where('vehiculo_id', $id)->first();

        if (!is_null($vehiculo)) {
            $vehiculo->delete();
            session()->set('_flash_success', 'Vehículo eliminado correctamente.');
        }

        return Response::redirect('/admin/vehiculos');
    }

    // --- helpers ---

    private function queryWithModelo()
    {
        return Vehiculo::query()
            ->from('Vehiculos')
            ->select('Vehiculos.*', 'Modelos.marca', 'Modelos.modelo')
            ->innerJoin('Modelos', 'Vehiculos.modelo_id', '=', 'Modelos.modelo_id');
    }

    private function findOrCreateModelo(string $marca, string $modelo): object
    {
        $reg = Modelo::where(['marca' => $marca, 'modelo' => $modelo])->first();

        return $reg ?? Modelo::create(['marca' => $marca, 'modelo' => $modelo]);
    }
}
