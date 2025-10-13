<?php

namespace App\Http\Controllers;

use App\Models\ActividadEconomica;
use Illuminate\Http\Request;

class ActividadEconomicaController extends Controller
{
    /**
     * Listar todas las actividades (activas e inactivas)
     */
    public function index()
    {
        $actividades = ActividadEconomica::with(['funcion:id,nombre'])
            ->withTrashed()
            ->get();

        return response()->json($actividades);
    }

    /**
     * Listar solo actividades activas
     */
    public function activas()
    {
        $actividades = ActividadEconomica::where('estado', 1)->get();
        return response()->json($actividades);
    }

    /**
     * Crear nueva actividad
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'funcion_id' => 'required|exists:funciones,id',
        ]);

        $actividad = ActividadEconomica::create([
            'descripcion' => $request->descripcion,
            'funcion_id' => $request->funcion_id,
        ]);

        return response()->json($actividad, 201);
    }

    /**
     * Mostrar actividad específica
     */
    public function show($id)
    {
        $actividad = ActividadEconomica::withTrashed()->findOrFail($id);
        return response()->json($actividad);
    }

    /**
     * Actualizar actividad
     */
    public function update(Request $request, $id)
    {
        $actividad = ActividadEconomica::withTrashed()->findOrFail($id);

        $request->validate([
            'descripcion' => 'required|string',
            'funcion_id' => 'required|exists:funciones,id',
        ]);

        $actividad->update([
            'descripcion' => $request->descripcion,
            'funcion_id' => $request->funcion_id,
        ]);

        return response()->json($actividad);
    }

    /**
     * Soft delete (marca estado = 0)
     */
    public function destroy($id)
    {
        $actividad = ActividadEconomica::findOrFail($id);
        $actividad->softDeleteWithEstado();
        return response()->json(['message' => 'Actividad eliminada (soft delete)']);
    }

    /**
     * Restaurar actividad eliminada
     */
    public function restore($id)
    {
        $actividad = ActividadEconomica::withTrashed()->findOrFail($id);
        $actividad->restoreWithEstado();
        return response()->json(['message' => 'Actividad restaurada']);
    }

}
