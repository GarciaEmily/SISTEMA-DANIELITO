<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Nino;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * CRUD de niños: listado agrupado por grupo (con vista separada para
 * Maestro, que solo ve los suyos), alta/edición con los datos de la
 * ficha y su grupo/maestro asignado, ficha individual y baja.
 */
class NinoController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Maestro: solo ve sus niños, agrupados por grupo
        if ($user->role->nombre === 'Maestro') {
            $ninosAgrupados = Nino::with(['maestro', 'grupo'])
                ->where('maestro_id', $user->id)
                ->get()
                ->groupBy(function ($nino) {
                    return $nino->grupo->nombre ?? 'Sin grupo';
                });

            return view('ninos.index_maestro', compact('ninosAgrupados'));
        }

        // Directora / Administrador: ven todos agrupados por grupo
        $ninosAgrupados = Nino::with(['maestro', 'grupo'])
            ->get()
            ->groupBy(function ($nino) {
                return $nino->grupo->nombre ?? 'Sin grupo';
            });

        // Mapa nombre => id para el botón de "reporte de asistencia por
        // grupo": se resuelve por el nombre del grupo (la clave del bloque),
        // no por el grupo de un niño cualquiera de la lista, para que no
        // dependa de que el primer niño del bloque tenga grupo asignado.
        $grupoPorNombre = Grupo::pluck('id', 'nombre');

        return view('ninos.index_admin', compact('ninosAgrupados', 'grupoPorNombre'));
    }

    public function create()
    {
        $grupos = Grupo::where('activo', true)->orderBy('nombre')->get();

        return view('ninos.create', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:ninos,codigo',
            'grupo_id' => 'required|exists:grupos,id',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'contacto' => 'nullable|string|max:255',
            'curso' => 'nullable|string|max:255',
            'colegio' => 'nullable|string|max:255',
            'motivo_vulnerabilidad' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'nombre_iglesia' => 'nullable|string|max:255',
            'nombre_celula' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $grupo = Grupo::findOrFail($request->grupo_id);
        $maestro = $grupo->maestro;

        if (! $maestro) {
            return back()->withErrors([
                'grupo_id' => 'El grupo seleccionado no tiene un maestro asignado.',
            ])->withInput();
        }

        $edad = null;
        if ($request->filled('fecha_nacimiento')) {
            $edad = Carbon::parse($request->fecha_nacimiento)->age;
        }

        Nino::create([
            'codigo' => $request->codigo,
            'maestro_id' => $maestro->id,
            'grupo_id' => $request->grupo_id,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'edad' => $edad,
            'contacto' => $request->contacto,
            'curso' => $request->curso,
            'colegio' => $request->colegio,
            'vulnerable' => $request->boolean('vulnerable'),
            'motivo_vulnerabilidad' => $request->motivo_vulnerabilidad,
            'observaciones' => $request->observaciones,
            'fue_al_encuentro' => $request->boolean('fue_al_encuentro'),
            'bautizado' => $request->boolean('bautizado'),
            'asiste_iglesia' => $request->boolean('asiste_iglesia'),
            'nombre_iglesia' => $request->nombre_iglesia,
            'nombre_celula' => $request->nombre_celula,
            'activo' => $request->boolean('activo'),
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        return redirect()->route('ninos.index')->with('success', 'Niño registrado correctamente.');
    }

    public function show(Nino $nino)
    {
        $user = auth()->user();

        // Si es maestro, solo puede ver sus propios niños
        if ($user->role->nombre === 'Maestro' && $nino->maestro_id !== $user->id) {
            abort(403, 'No autorizado');
        }

        $nino->load(['maestro', 'grupo']);

        return view('ninos.show', compact('nino'));
    }

    public function edit(Nino $nino)
    {
        // Solo grupos activos, salvo el que el niño ya tiene asignado
        // (aunque esté inactivo), para no perder esa asignación al editar
        // otros campos sin querer tocar el grupo.
        $grupos = Grupo::where(function ($query) use ($nino) {
            $query->where('activo', true);

            if ($nino->grupo_id) {
                $query->orWhere('id', $nino->grupo_id);
            }
        })
            ->orderBy('nombre')
            ->get();

        return view('ninos.edit', compact('nino', 'grupos'));
    }

    public function update(Request $request, Nino $nino)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:ninos,codigo,'.$nino->id,
            'grupo_id' => 'required|exists:grupos,id',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'contacto' => 'nullable|string|max:255',
            'curso' => 'nullable|string|max:255',
            'colegio' => 'nullable|string|max:255',
            'motivo_vulnerabilidad' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'nombre_iglesia' => 'nullable|string|max:255',
            'nombre_celula' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric', // Al ser nullable, no romperá si no eligen mapa
            'longitud' => 'nullable|numeric',
        ]);

        $grupo = Grupo::findOrFail($request->grupo_id);
        $maestro = $grupo->maestro;

        if (! $maestro) {
            return back()->withErrors([
                'grupo_id' => 'El grupo seleccionado no tiene un maestro asignado.',
            ])->withInput();
        }

        $edad = null;
        if ($request->filled('fecha_nacimiento')) {
            $edad = Carbon::parse($request->fecha_nacimiento)->age;
        }

        $nino->update([
            'codigo' => $request->codigo,
            'maestro_id' => $maestro->id,
            'grupo_id' => $request->grupo_id,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'edad' => $edad,
            'contacto' => $request->contacto,
            'curso' => $request->curso,
            'colegio' => $request->colegio,
            'vulnerable' => $request->boolean('vulnerable'),
            'motivo_vulnerabilidad' => $request->motivo_vulnerabilidad,
            'observaciones' => $request->observaciones,
            'fue_al_encuentro' => $request->boolean('fue_al_encuentro'),
            'bautizado' => $request->boolean('bautizado'),
            'asiste_iglesia' => $request->boolean('asiste_iglesia'),
            'nombre_iglesia' => $request->nombre_iglesia,
            'nombre_celula' => $request->nombre_celula,
            'activo' => $request->boolean('activo'),
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        return redirect()->route('ninos.index')->with('success', 'Niño actualizado correctamente.');
    }

    public function destroy(Nino $nino)
    {
        $totalAsistencias = $nino->asistencias()->count();
        $totalVisitas = $nino->visitas()->count();

        if ($totalAsistencias > 0 || $totalVisitas > 0) {
            $razones = [];

            if ($totalAsistencias > 0) {
                $razones[] = $totalAsistencias.' registro(s) de asistencia';
            }
            if ($totalVisitas > 0) {
                $razones[] = $totalVisitas.' visita(s) registrada(s)';
            }

            return back()->with(
                'error',
                'No se puede eliminar a '.$nino->nombres.' '.$nino->apellidos
                    .' porque tiene '.implode(' y ', $razones)
                    .'. Desactívalo en su lugar desde "Editar" para dejar de asignarlo a nada nuevo sin perder ese historial.'
            );
        }

        $nino->delete();

        return redirect()->route('ninos.index')->with('success', 'Niño eliminado correctamente.');
    }
}
