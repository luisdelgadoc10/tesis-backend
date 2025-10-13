<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // ⚡ Crear token en lugar de sesión
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Usuario registrado exitosamente'
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::withTrashed()->where('email', $credentials['email'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        // RECHAZAR login de usuarios inactivos desde el inicio
        if ($user->estado != 1) {
            return response()->json([
                'message' => 'Usuario inactivo. Contacte al administrador.'
            ], 403);
        }

        Auth::login($user);
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function profile(Request $request)
    {
        // ✅ Carga roles, permisos directos y permisos de roles
        $user = $request->user()->load([
            'roles',
            'permissions',
            'roles.permissions' // ← ¡Esto es clave!
        ]);

        // ✅ Combina permisos directos + permisos de roles
        $allPermissions = $user->permissions->merge(
            $user->roles->flatMap(function ($role) {
                return $role->permissions;
            })
        )->unique('id');

        // ✅ Devuelve el usuario con todos los permisos
        $user->setRelation('permissions', $allPermissions);

        return response()->json([
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // ⚡ Eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }
}