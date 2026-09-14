<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('role')->orderBy('nombre')->orderBy('apellido')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'email.unique' => 'Ya existe un usuario con ese correo electrónico.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], [
            'email.unique' => 'Ya existe un usuario con ese correo electrónico.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $datos = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'activo' => $request->boolean('activo', true),
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $asociaciones = [];

        if ($usuario->ninos()->exists()) {
            $asociaciones[] = 'niños asignados como maestro';
        }
        if ($usuario->gruposComoMaestro()->exists()) {
            $asociaciones[] = 'grupos asignados como maestro';
        }
        if ($usuario->actividades()->exists()) {
            $asociaciones[] = 'actividades creadas';
        }
        if ($usuario->asistenciasRegistradas()->exists()) {
            $asociaciones[] = 'asistencias registradas';
        }
        if ($usuario->visitasRealizadas()->exists()) {
            $asociaciones[] = 'visitas realizadas';
        }

        if (!empty($asociaciones)) {
            return back()->with(
                'error',
                'No se puede eliminar a ' . $usuario->nombre . ' ' . $usuario->apellido
                    . ' porque tiene ' . implode(', ', $asociaciones) . ' asociados. '
                    . 'Desactívalo en su lugar desde "Editar" para quitarle el acceso sin perder ese historial.'
            );
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
