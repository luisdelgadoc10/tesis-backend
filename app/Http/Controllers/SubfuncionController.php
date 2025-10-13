<?php

namespace App\Http\Controllers;

use App\Models\Subfuncion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubfuncionController extends Controller
{
    /**
     * Listar todas las subfunciones con sus relaciones.
     */
    public function index()
    {
        $subfunciones = Subfuncion::with(['funcion:id,nombre', 'riesgoIncendio:id,nombre', 'riesgoColapso:id,nombre'])->get();

        return response()->json($subfunciones);
    }

    /**
     * Guardar una nueva subfunción.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'funcion_id'      => 'required|exists:funciones,id',
            'codigo'          => 'required|string|max:20|unique:subfunciones,codigo',
            'descripcion'     => 'required|string|max:255',
            'riesgo_incendio' => 'required|exists:niveles_riesgo,id',
            'riesgo_colapso'  => 'required|exists:niveles_riesgo,id',
        ]);

        $subfuncion = Subfuncion::create($validated);

        return response()->json([
            'message' => 'Subfunción creada correctamente',
            'data'    => $subfuncion->load(['funcion', 'riesgoIncendio', 'riesgoColapso']),
        ], 201);
    }

    /**
     * Mostrar una subfunción específica.
     */
    public function show($id)
    {
        $subfuncion = Subfuncion::with(['funcion', 'riesgoIncendio', 'riesgoColapso'])->findOrFail($id);
        return response()->json($subfuncion);
    }

    /**
     * Actualizar una subfunción.
     */
    public function update(Request $request, $id)
    {
        $subfuncion = Subfuncion::findOrFail($id);

        $validated = $request->validate([
            'funcion_id'      => 'required|exists:funciones,id',
            'codigo'          => [
                'required', 'string', 'max:20',
                Rule::unique('subfunciones')->ignore($subfuncion->id),
            ],
            'descripcion'     => 'required|string|max:255',
            'riesgo_incendio' => 'required|exists:niveles_riesgo,id',
            'riesgo_colapso'  => 'required|exists:niveles_riesgo,id',
        ]);

        $subfuncion->update($validated);

        return response()->json([
            'message' => 'Subfunción actualizada correctamente',
            'data'    => $subfuncion->load(['funcion', 'riesgoIncendio', 'riesgoColapso']),
        ]);
    }

}
