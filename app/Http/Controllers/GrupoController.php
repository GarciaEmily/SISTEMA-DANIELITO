<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with('maestro')
            ->withCount('ninos')
            ->orderBy('nombre')
            ->get();

        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        $maestros = $this->obtenerMaestros();

        return view('grupos.create', compact('maestros'));
    }

    public function store(Request $request)
    {
        $request->validate($this->reglasValidacion());

        Grupo::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'maestro_id' => $request->filled('maestro_id') ? $request->maestro_id : null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo creado correctamente.');
    }

    public function edit(Grupo $grupo)
    {
        $maestros = $this->obtenerMaestros();

        return view('grupos.edit', compact('grupo', 'maestros'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate($this->reglasValidacion($grupo));

        $grupo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'maestro_id' => $request->filled('maestro_id') ? $request->maestro_id : null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('grupos.index')->with('success', 'Grupo actualizado correctamente.');
    }

    public function destroy(Grupo $grupo)
    {
        $totalNinos = $grupo->ninos()->count();
        $totalActividades = $grupo->actividades()->count();

        if ($totalNinos > 0 || $totalActividades > 0) {
            $razones = [];

            if ($totalNinos > 0) {
                $razones[] = $totalNinos.' niño(s) asignado(s)';
            }
            if ($totalActividades > 0) {
                $razones[] = $totalActividades.' actividad(es) asociada(s)';
            }

            return back()->with(
                'error',
                'No se puede eliminar el grupo "'.$grupo->nombre.'" porque tiene '
                    .implode(' y ', $razones)
                    .'. Desactívalo en su lugar desde "Editar" para dejar de usarlo sin perder esos registros.'
            );
        }

        $grupo->delete();

        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado correctamente.');
    }

    private function reglasValidacion(?Grupo $grupo = null): array
    {
        $maestroRoleId = Role::where('nombre', 'Maestro')->value('id');

        return [
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('grupos', 'nombre')->ignore($grupo?->id),
            ],
            'descripcion' => 'nullable|string',
            'maestro_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role_id', $maestroRoleId),
            ],
        ];
    }

    private function obtenerMaestros()
    {
        return User::whereHas('role', function ($query) {
            $query->where('nombre', 'Maestro');
        })
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();
    }
}
