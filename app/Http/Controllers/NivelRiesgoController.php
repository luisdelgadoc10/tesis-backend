<?php

namespace App\Http\Controllers;

use App\Models\NivelRiesgo;
use Illuminate\Http\Request;

class NivelRiesgoController extends Controller
{
    /**
     * Muestra todos los niveles de riesgo
     */
    public function index()
    {
        $niveles = NivelRiesgo::all();
        return response()->json($niveles);
    }

    /**
     * Muestra un nivel de riesgo específico
     */
    public function show($id)
    {
        $nivel = NivelRiesgo::find($id);

        if (!$nivel) {
            return response()->json(['message' => 'Nivel de riesgo no encontrado'], 404);
        }

        return response()->json($nivel);
    }

    /**
     * Crea un nuevo nivel de riesgo
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $nivel = NivelRiesgo::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'message' => 'Nivel de riesgo creado correctamente',
            'data' => $nivel,
        ], 201);
    }

    /**
     * Actualiza un nivel de riesgo existente
     */
    public function update(Request $request, $id)
    {
        $nivel = NivelRiesgo::find($id);

        if (!$nivel) {
            return response()->json(['message' => 'Nivel de riesgo no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $nivel->update([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'message' => 'Nivel de riesgo actualizado correctamente',
            'data' => $nivel,
        ]);
    }

    // /**
    //  * Elimina un nivel de riesgo
    //  */
    // public function destroy($id)
    // {
    //     $nivel = NivelRiesgo::find($id);

    //     if (!$nivel) {
    //         return response()->json(['message' => 'Nivel de riesgo no encontrado'], 404);
    //     }

    //     $nivel->delete();

    //     return response()->json(['message' => 'Nivel de riesgo eliminado correctamente']);
    // }
}
