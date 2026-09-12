<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')
            ->orderBy('name')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'codigo_empleado' => 'nullable|string|max:255|unique:users,codigo_empleado',
            'role' => 'required|exists:roles,name',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'codigo_empleado' => $datos['codigo_empleado'] ?? null,
            'password' => Hash::make($datos['password']),
            'activo' => true,
        ]);

        $usuario->assignRole($datos['role']);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::orderBy('name')->get();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'codigo_empleado' => 'nullable|string|max:255|unique:users,codigo_empleado,' . $usuario->id,
            'role' => 'required|exists:roles,name',
            'activo' => 'required|boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];
        $usuario->codigo_empleado = $datos['codigo_empleado'] ?? null;
        $usuario->activo = $datos['activo'];

        if (!empty($datos['password'])) {
            $usuario->password = Hash::make($datos['password']);
        }

        $usuario->save();

        $usuario->syncRoles([$datos['role']]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}