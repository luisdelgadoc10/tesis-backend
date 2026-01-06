<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Establecimientos",
 *     description="Gestión de establecimientos"
 * )
 */
class EstablecimientoController extends Controller
{
    /**
     * Listar todos los establecimientos activos
     *
     * @OA\Get(
     *     path="/api/establecimientos",
     *     tags={"Establecimientos"},
     *     summary="Listar establecimientos activos",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de establecimientos activos"
     *     )
     * )
     */
    public function index()
    {
        $establecimientos = Establecimiento::where('estado', 1)->get();
        return response()->json($establecimientos);
    }

    /**
     * Listar TODOS los establecimientos (incluyendo eliminados lógicamente)
     *
     * @OA\Get(
     *     path="/api/establecimientos/todos",
     *     tags={"Establecimientos"},
     *     summary="Listar todos los establecimientos",
     *     description="Incluye establecimientos activos e inactivos (soft delete)",
     *     @OA\Response(
     *         response=200,
     *         description="Listado completo de establecimientos"
     *     )
     * )
     */
    public function todos()
    {
        $establecimientos = Establecimiento::withTrashed()->get();
        return response()->json($establecimientos);
    }

    /**
     * Crear un nuevo establecimiento
     *
     * @OA\Post(
     *     path="/api/establecimientos",
     *     tags={"Establecimientos"},
     *     summary="Crear un establecimiento",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre_comercial","razon_social","ruc","direccion","propietario","telefono"},
     *             @OA\Property(property="nombre_comercial", type="string", example="Botica Central"),
     *             @OA\Property(property="razon_social", type="string", example="Botica Central SAC"),
     *             @OA\Property(property="ruc", type="string", example="20123456789"),
     *             @OA\Property(property="direccion", type="string", example="Av. Principal 123"),
     *             @OA\Property(property="latitud", type="number", format="float", example=-12.04318),
     *             @OA\Property(property="longitud", type="number", format="float", example=-77.02824),
     *             @OA\Property(property="actividad_economica_id", type="integer", example=3),
     *             @OA\Property(property="propietario", type="string", example="Juan Pérez"),
     *             @OA\Property(property="telefono", type="string", example="987654321"),
     *             @OA\Property(property="correo_electronico", type="string", example="contacto@empresa.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Establecimiento creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
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

        $validated['estado'] = 1;

        $establecimiento = Establecimiento::create($validated);

        return response()->json($establecimiento, 201);
    }

    /**
     * Mostrar un establecimiento activo por ID
     *
     * @OA\Get(
     *     path="/api/establecimientos/{id}",
     *     tags={"Establecimientos"},
     *     summary="Obtener un establecimiento activo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Establecimiento encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Establecimiento no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $establecimiento = Establecimiento::where('id', $id)
            ->where('estado', 1)
            ->firstOrFail();

        return response()->json($establecimiento, 200);
    }

    /**
     * Actualizar un establecimiento
     *
     * @OA\Put(
     *     path="/api/establecimientos/{id}",
     *     tags={"Establecimientos"},
     *     summary="Actualizar un establecimiento",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre_comercial", type="string"),
     *             @OA\Property(property="razon_social", type="string"),
     *             @OA\Property(property="ruc", type="string"),
     *             @OA\Property(property="direccion", type="string"),
     *             @OA\Property(property="latitud", type="number"),
     *             @OA\Property(property="longitud", type="number"),
     *             @OA\Property(property="actividad_economica_id", type="integer"),
     *             @OA\Property(property="propietario", type="string"),
     *             @OA\Property(property="telefono", type="string"),
     *             @OA\Property(property="correo_electronico", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Establecimiento actualizado correctamente"
     *     )
     * )
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
        $establecimiento->update($validated);

        return response()->json($establecimiento, 200);
    }

    /**
     * Eliminar un establecimiento (baja lógica)
     *
     * @OA\Delete(
     *     path="/api/establecimientos/{id}",
     *     tags={"Establecimientos"},
     *     summary="Eliminar un establecimiento",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Establecimiento eliminado lógicamente"
     *     )
     * )
     */
    public function destroy($id)
    {
        $establecimiento = Establecimiento::findOrFail($id);

        $establecimiento->estado = 0;
        $establecimiento->save();
        $establecimiento->delete();

        return response()->json(['message' => 'Establecimiento desactivado y eliminado lógicamente'], 200);
    }

    /**
     * Restaurar un establecimiento eliminado
     *
     * @OA\Patch(
     *     path="/api/establecimientos/{id}/restore",
     *     tags={"Establecimientos"},
     *     summary="Restaurar un establecimiento",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Establecimiento restaurado correctamente"
     *     )
     * )
     */
    public function restore($id)
    {
        $establecimiento = Establecimiento::onlyTrashed()->findOrFail($id);

        $establecimiento->restore();
        $establecimiento->estado = 1;
        $establecimiento->save();

        return response()->json(['message' => 'Establecimiento restaurado correctamente'], 200);
    }
}
