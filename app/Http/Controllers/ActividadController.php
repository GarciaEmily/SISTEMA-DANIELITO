<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Grupo;
use App\Models\Nino;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role->nombre === 'Maestro') {
            $gruposPermitidos = $this->obtenerGruposDelMaestro($user);

            $actividades = Actividad::with('grupos')
                ->whereHas('grupos', function ($query) use ($gruposPermitidos) {
                    $query->whereIn('nombre', $gruposPermitidos);
                })
                ->get();

            return view('actividades.index_maestro', compact('actividades'));
        }

        $actividades = Actividad::with('grupos')->get();

        return view('actividades.index', compact('actividades'));
    }

    public function create()
    {
        $grupos = Grupo::all();

        return view('actividades.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_actividad' => 'nullable|date',
            'tipo' => 'required|in:normal,intervencion',

            // CAMBIO AQUÍ
            'grupos' => 'required|array',
            'grupos.*' => 'exists:grupos,id',

        ], [
            'nombre.required' => 'El nombre de la actividad es obligatorio.',
            'tipo.required' => 'Debes seleccionar el tipo de actividad.',
            'grupos.required' => 'Debes seleccionar al menos un grupo.',
        ]);
        // CREAR ACTIVIDAD
        $actividad = Actividad::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_actividad' => $request->fecha_actividad,
            'tipo' => $request->tipo,

            // guardar uno opcionalmente
            'grupo_id' => $request->grupos[0],

            'activa' => true,
            'creado_por' => auth()->id(),
        ]);

        // GUARDAR RELACIÓN MUCHOS A MUCHOS
        $actividad->grupos()->attach($request->grupos);

        return redirect()->route('actividades.index')
            ->with('success', 'Actividad registrada correctamente.');
    }

    public function edit(Actividad $actividad)
    {
        $grupos = Grupo::all();

        return view('actividades.edit', compact('actividad', 'grupos'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_actividad' => 'nullable|date',
            'tipo' => 'required|in:normal,intervencion',

            'grupos' => 'required|array',
            'grupos.*' => 'exists:grupos,id',
        ]);

        $actividad->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_actividad' => $request->fecha_actividad,
            'tipo' => $request->tipo,

            'grupo_id' => $request->grupos[0],
        ]);

        $actividad->grupos()->sync($request->grupos);

        return redirect()->route('actividades.index')
            ->with('success', 'Actividad actualizada correctamente.');
    }

    public function destroy(Actividad $actividad)
    {
        // eliminar relaciones con grupos
        $actividad->grupos()->detach();

        // eliminar relaciones con niños
        $actividad->ninos()->detach();

        // eliminar actividad
        $actividad->delete();

        return redirect()->route('actividades.index')
            ->with('success', 'Actividad eliminada correctamente.');
    }

    private function obtenerGruposDelMaestro($user)
    {
        $nombreCompleto = trim($user->nombre.' '.$user->apellido);

        return match ($nombreCompleto) {
            'Ivi Condori' => ['6 a 8 años'],
            'Danna Garcia' => ['9 a 11 años'],
            'Diego Chore' => ['12 a 14 años'],
            'Juan Carlos Contreras' => ['15 a 18 años'],
            default => [],
        };
    }

    public function asignarNinos(Actividad $actividad)
    {

        $actividad->load('grupos');

        $grupoIds = $actividad->grupos()->pluck('grupos.id')->toArray();

        $ninosAsignados = $actividad->ninos()->pluck('ninos.id')->toArray();

        // Solo niños activos, salvo los que ya estén asignados a esta actividad
        // (aunque se hayan desactivado después), para no perder esa asignación
        // al guardar sin querer tocarla.
        $ninosAgrupados = Nino::with('grupo')
            ->whereIn('grupo_id', $grupoIds)
            ->where(function ($query) use ($ninosAsignados) {
                $query->where('activo', true);

                if (! empty($ninosAsignados)) {
                    $query->orWhereIn('id', $ninosAsignados);
                }
            })
            ->get()
            ->groupBy(function ($nino) {
                return $nino->grupo->nombre ?? 'Sin grupo';
            });

        return view('actividades.asignar_ninos', compact(
            'actividad',
            'ninosAgrupados',
            'ninosAsignados'
        ));
    }

    public function guardarNinos(Request $request, Actividad $actividad)
    {
        $request->validate([
            'ninos' => 'nullable|array',
            'ninos.*' => 'exists:ninos,id',
        ]);

        $actividad->ninos()->sync($request->ninos ?? []);

        return redirect()->route('actividades.index')
            ->with('success', 'Niños asignados correctamente a la actividad.');
    }
}
