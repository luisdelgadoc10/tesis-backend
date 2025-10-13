<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Mostrar todos los usuarios con sus roles
     * GET /api/users
     */
    public function index()
    {
        $usuarios = User::withTrashed()->with('roles')->get();
        return response()->json($usuarios);
    }

    /**
     * Crear un nuevo usuario
     * POST /api/users
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'nullable|string|exists:roles,name', // 👈 opcional, rol al crear
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
     * GET /api/users/{id}
     */
    public function show(User $user)
    {
        return response()->json($user->load('roles'));
    }

    /**
     * Actualizar un usuario
     * PUT /api/users/{id}
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
            'roles'    => 'nullable|array',                // 👈 manejar varios roles
            'roles.*'  => 'string|exists:roles,name',      // validar cada rol
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

        // 👇 si roles está presente, reemplaza todos
        if (array_key_exists('roles', $validated)) {
            $user->syncRoles($validated['roles']);
        }

        return response()->json($user->load('roles'));
    }


    /**
     * Eliminar un usuario (Soft Delete)
     * DELETE /api/users/{id}
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    /**
     * Restaurar un usuario eliminado
     * PUT /api/users/{id}/restore
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return response()->json($user->load('roles'));
    }

}
