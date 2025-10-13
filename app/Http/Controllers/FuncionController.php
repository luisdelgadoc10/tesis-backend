<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use Illuminate\Http\Request;

class FuncionController extends Controller
{
    /**
     * Listar todas las funciones 
     */
    public function index()
    {
        $funciones = Funcion::withTrashed()->get();
        return response()->json($funciones);
    }

    /**
     * Listar solo funciones activas
     */
    public function activas()
    {
        $funciones = Funcion::where('estado', 1)->get();
        return response()->json($funciones);
    }

    /**
     * Crear nueva función
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:funciones,nombre',
        ]);

        $funcion = Funcion::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json($funcion, 201);
    }

    /**
     * Mostrar función específica
     */
    public function show($id)
    {
        $funcion = Funcion::withTrashed()->findOrFail($id);
        return response()->json($funcion);
    }

    /**
     * Actualizar función
     */
    public function update(Request $request, $id)
    {
        $funcion = Funcion::withTrashed()->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:funciones,nombre,' . $funcion->id,
        ]);

        $funcion->update([
            'nombre' => $request->nombre,
        ]);

        return response()->json($funcion);
    }

    /**
     * Soft delete (marca estado = 0)
     */
    public function destroy($id)
    {
        $funcion = Funcion::findOrFail($id);
        $funcion->delete(); // ya maneja estado automáticamente
        return response()->json(['message' => 'Función eliminada (soft delete)']);
    }

    /**
     * Restaurar función eliminada
     */
    public function restore($id)
    {
        $funcion = Funcion::withTrashed()->findOrFail($id);
        $funcion->restore(); // estado = 1 automáticamente
        return response()->json(['message' => 'Función restaurada']);
    }
}
