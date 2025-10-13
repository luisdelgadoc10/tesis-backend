<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use Illuminate\Http\Request;

class EstablecimientoController extends Controller
{
    /**
     * Listar todos los establecimientos activos
     * GET /api/establecimientos
     */
    public function index()
    {
        $establecimientos = Establecimiento::where('estado', 1)->get();
        return response()->json($establecimientos);
    }

    /**
     * Listar TODOS los establecimientos (incluyendo eliminados lógicamente)
     * GET /api/establecimientos/todos
     */
    public function todos()
    {
        $establecimientos = Establecimiento::withTrashed()->get();
        return response()->json($establecimientos);
    }

    /**
     * Crear un nuevo establecimiento
     * POST /api/establecimientos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_comercial'   => 'required|string|max:255',
            'razon_social'       => 'required|string|max:255',
            'ruc'                => 'required|string|size:11|unique:establecimientos,ruc',
            'direccion'          => 'required|string|max:255',
            'latitud'            => 'nullable|numeric|between:-90,90',
            'longitud'           => 'nullable|numeric|between:-180,180',
            'actividad_economica_id' => 'nullable|exists:actividad_economica,id',
            'propietario'        => 'required|string|max:255',
            'telefono'           => 'required|string|max:9',
            'correo_electronico' => 'nullable|email|max:255',
        ]);

        // Forzar estado en 1
        $validated['estado'] = 1;

        $establecimiento = Establecimiento::create($validated);

        return response()->json($establecimiento, 201);
    }

    /**
     * Mostrar un establecimiento activo por ID
     * GET /api/establecimientos/{id}
     */
    public function show($id)
    {
        $establecimiento = Establecimiento::where('id', $id)
            ->where('estado', 1)
            ->firstOrFail();

        return response()->json($establecimiento, 200);
    }

    /**
     * Actualizar un establecimiento (no permite cambiar estado)
     * PUT/PATCH /api/establecimientos/{id}
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre_comercial'   => 'sometimes|required|string|max:255',
            'razon_social'       => 'sometimes|required|string|max:255',
            'ruc'                => 'sometimes|required|string|size:11|unique:establecimientos,ruc,' . $id,
            'direccion'          => 'sometimes|required|string|max:255',
            'latitud'                => 'nullable|numeric|between:-90,90',
            'longitud'               => 'nullable|numeric|between:-180,180',
            'actividad_economica_id' => 'nullable|exists:actividad_economica,id',
            'propietario'        => 'sometimes|required|string|max:255',
            'telefono'           => 'sometimes|required|string|max:9',
            'correo_electronico' => 'nullable|email|max:255',
        ]);

        $establecimiento = Establecimiento::findOrFail($id);

        // Protegemos que estado no cambie
        $establecimiento->update($validated);

        return response()->json($establecimiento, 200);
    }

    /**
     * Eliminar un establecimiento
     * DELETE /api/establecimientos/{id}
     * - Cambia estado a 0
     * - Aplica SoftDelete
     */
    public function destroy($id)
    {
        $establecimiento = Establecimiento::findOrFail($id);

        // Marcar como inactivo
        $establecimiento->estado = 0;
        $establecimiento->save();

        // Soft delete
        $establecimiento->delete();

        return response()->json(['message' => 'Establecimiento desactivado y eliminado lógicamente'], 200);
    }

    /**
     * Restaurar un establecimiento eliminado
     * PATCH /api/establecimientos/{id}/restore
     */
    public function restore($id)
    {
        $establecimiento = Establecimiento::onlyTrashed()->findOrFail($id);

        $establecimiento->restore();
        $establecimiento->estado = 1; // volver a activo
        $establecimiento->save();

        return response()->json(['message' => 'Establecimiento restaurado correctamente'], 200);
    }
}
