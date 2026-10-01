<?php

namespace App\Http\Controllers;

use App\Models\Nino;
use App\Models\Visita;
use Illuminate\Http\Request;

class VisitaController extends Controller
{
    public function index(Request $request)
    {
        $visitas = Visita::with(['nino.grupo', 'realizadoPor'])
            ->when($request->filled('nino_id'), function ($query) use ($request) {
                $query->where('nino_id', $request->nino_id);
            })
            ->orderBy('fecha_visita', 'desc')
            ->get();

        $ninoFiltro = $request->filled('nino_id')
            ? Nino::find($request->nino_id)
            : null;

        return view('visitas.index', compact('visitas', 'ninoFiltro'));
    }

    public function create(Request $request)
    {
        // Solo niños activos (no eliminados): Nino ya filtra los soft-deleted
        // por el scope global, no hace falta onlyTrashed()/withTrashed() acá.
        $ninos = Nino::with('grupo')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        $ninoSeleccionado = $request->filled('nino_id')
            ? (int) $request->nino_id
            : null;

        return view('visitas.create', compact('ninos', 'ninoSeleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nino_id' => 'required|exists:ninos,id',
            'fecha_visita' => 'required|date|before_or_equal:today',
            'motivo' => 'nullable|string',
            'observacion' => 'nullable|string',
            'seguimiento' => 'nullable|string',
            // Evidencia obligatoria solo al crear: formatos tipicos de
            // camara/navegador movil, hasta 8MB.
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        $fotoPath = $request->file('foto')->store('visitas', 'public');

        Visita::create([
            'nino_id' => $request->nino_id,
            'realizado_por' => auth()->id(),
            'fecha_visita' => $request->fecha_visita,
            'motivo' => $request->motivo,
            'observacion' => $request->observacion,
            'seguimiento' => $request->seguimiento,
            'foto_path' => $fotoPath,
        ]);

        return redirect()->route('visitas.index')->with('success', 'Visita registrada correctamente.');
    }

    public function edit(Visita $visita)
    {
        $visita->load(['nino.grupo', 'realizadoPor']);

        return view('visitas.edit', compact('visita'));
    }

    public function update(Request $request, Visita $visita)
    {
        // La fecha de la visita no se incluye en la validacion ni en el
        // update(): una vez creada la visita es inmutable, aunque alguien
        // manipule el formulario para mandarla igual.
        $request->validate([
            'motivo' => 'nullable|string',
            'observacion' => 'nullable|string',
            'seguimiento' => 'nullable|string',
        ]);

        $visita->update([
            'motivo' => $request->motivo,
            'observacion' => $request->observacion,
            'seguimiento' => $request->seguimiento,
        ]);

        return redirect()->route('visitas.index')->with('success', 'Visita actualizada correctamente.');
    }

    public function destroy(Visita $visita)
    {
        $visita->delete();

        return redirect()->route('visitas.index')->with('success', 'Visita eliminada correctamente.');
    }
}
