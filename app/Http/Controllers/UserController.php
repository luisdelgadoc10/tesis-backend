<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

/**
 * @OA\Tag(
 *     name="Usuarios",
 *     description="Gestión de usuarios y asignación de roles"
 * )
 */
class UserController extends Controller
{
    /**
     * Mostrar todos los usuarios con sus roles
     *
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Usuarios"},
     *     summary="Listar usuarios",
     *     description="Obtiene todos los usuarios, incluyendo los eliminados (soft delete), junto con sus roles",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de usuarios",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="email", type="string", example="juan@example.com"),
     *                 @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="roles", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="admin")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $usuarios = User::withTrashed()->with('roles')->get();
        return response()->json($usuarios);
    }

    /**
     * Crear un nuevo usuario
     *
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Usuarios"},
     *     summary="Crear usuario",
     *     description="Registra un nuevo usuario y opcionalmente asigna un rol",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="María López"),
     *             @OA\Property(property="email", type="string", example="maria@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado correctamente"
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'nullable|string|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (!empty($validated['role'])) {
            $user->assignRole($validated['role']);
        }

        return response()->json($user->load('roles'), 201);
    }

    /**
     * Mostrar un usuario específico
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Usuarios"},
     *     summary="Obtener usuario",
     *     description="Obtiene el detalle de un usuario específico con sus roles",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle del usuario"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function show(User $user)
    {
        return response()->json($user->load('roles'));
    }

    /**
     * Actualizar un usuario
     *
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Usuarios"},
     *     summary="Actualizar usuario",
     *     description="Actualiza la información del usuario y sus roles",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Juan Actualizado"),
     *             @OA\Property(property="email", type="string", example="juan.new@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="roles", type="array",
     *                 @OA\Items(type="string", example="admin")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'roles'    => 'nullable|array',
            'roles.*'  => 'string|exists:roles,name',
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (array_key_exists('roles', $validated)) {
            $user->syncRoles($validated['roles']);
        }

        return response()->json($user->load('roles'));
    }

    /**
     * Eliminar un usuario (Soft Delete)
     *
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Usuarios"},
     *     summary="Eliminar usuario",
     *     description="Elimina un usuario mediante soft delete",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuario eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    /**
     * Restaurar un usuario eliminado
     *
     * @OA\Put(
     *     path="/api/users/{id}/restore",
     *     tags={"Usuarios"},
     *     summary="Restaurar usuario",
     *     description="Restaura un usuario eliminado previamente (soft delete)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario restaurado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return response()->json($user->load('roles'));
    }
}
