<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Controller;
use Src\App\Models\Cliente;
use Src\Http\Request;
use Src\Http\Response;
use Src\View\View;

class ClienteController extends Controller
{
    public function index(): View
    {
        return new View(fromViews('admin/clientes/index.php'), [
            'clientes' => Cliente::orderBy('cliente_id', 'desc')->get(),
        ]);
    }

    public function create(): View
    {
        return new View(fromViews('admin/clientes/crear.php'));
    }

    public function store(Request $request): Response
    {
        $this->validate(
            data: [
                'nombre'              => $request->body('nombre'),
                'apellido'            => $request->body('apellido'),
                'documento_identidad' => $request->body('documento_identidad'),
            ],
            validations: [
                'nombre'              => 'required',
                'apellido'            => 'required',
                'documento_identidad' => 'required',
            ],
            url: '/admin/clientes/crear'
        );

        Cliente::create([
            'nombre'              => $request->body('nombre'),
            'apellido'            => $request->body('apellido'),
            'documento_identidad' => $request->body('documento_identidad'),
            'telefono'            => $request->body('telefono') ?: null,
            'email'               => $request->body('email') ?: null,
            'direccion'           => $request->body('direccion') ?: null,
        ]);

        session()->set('_flash_success', 'Cliente creado correctamente.');

        return Response::redirect('/admin/clientes');
    }

    public function edit(int $id): View|Response
    {
        $cliente = Cliente::where('cliente_id', $id)->first();

        if (is_null($cliente)) {
            session()->set('_flash_success', 'Cliente no encontrado.');
            return Response::redirect('/admin/clientes');
        }

        return new View(fromViews('admin/clientes/editar.php'), [
            'cliente' => $cliente,
        ]);
    }

    public function update(Request $request, int $id): Response
    {
        $cliente = Cliente::where('cliente_id', $id)->first();

        if (is_null($cliente)) {
            return Response::redirect('/admin/clientes');
        }

        $this->validate(
            data: [
                'nombre'              => $request->body('nombre'),
                'apellido'            => $request->body('apellido'),
                'documento_identidad' => $request->body('documento_identidad'),
            ],
            validations: [
                'nombre'              => 'required',
                'apellido'            => 'required',
                'documento_identidad' => 'required',
            ],
            url: "/admin/clientes/{$id}/editar"
        );

        $cliente->update([
            'nombre'              => $request->body('nombre'),
            'apellido'            => $request->body('apellido'),
            'documento_identidad' => $request->body('documento_identidad'),
            'telefono'            => $request->body('telefono') ?: null,
            'email'               => $request->body('email') ?: null,
            'direccion'           => $request->body('direccion') ?: null,
        ]);

        session()->set('_flash_success', 'Cliente actualizado correctamente.');

        return Response::redirect('/admin/clientes');
    }

    public function destroy(int $id): Response
    {
        $cliente = Cliente::where('cliente_id', $id)->first();

        if (!is_null($cliente)) {
            $cliente->delete();
            session()->set('_flash_success', 'Cliente eliminado correctamente.');
        }

        return Response::redirect('/admin/clientes');
    }
}
