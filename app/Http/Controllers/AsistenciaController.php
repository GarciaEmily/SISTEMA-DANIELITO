<?php

namespace App\Http\Controllers;

use App\Exports\AsistenciaExport;
use App\Models\Actividad;
use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AsistenciaController extends Controller
{
    public function create(Actividad $actividad)
    {
        $actividad->load('grupo');

        // Solo niños activos: uno desactivado no debe poder recibir
        // asistencia nueva desde el momento en que se desactiva en
        // adelante. Su historial ya registrado no se toca acá (vive en
        // AsistenciaController::index() y en los reportes, que no filtran
        // por activo y no deben empezar a hacerlo).
        $ninos = $actividad->ninos()->where('activo', true)->get();

        return view('asistencia.create', compact('actividad', 'ninos'));
    }

    public function store(Request $request, Actividad $actividad)
    {
        // La cota inferior solo aplica si la actividad tiene una fecha fija
        // (tipo "normal"); una "Intervención" es recurrente y no tiene una
        // fecha única contra la cual comparar (fecha_actividad es nullable).
        $reglasFecha = ['required', 'date', 'before_or_equal:today'];

        if ($actividad->fecha_actividad) {
            $reglasFecha[] = 'after_or_equal:'.$actividad->fecha_actividad;
        }

        $request->validate([
            'fecha' => $reglasFecha,
            'asistencias' => ['required', 'array'],
        ], [
            'fecha.after_or_equal' => 'La fecha de asistencia no puede ser anterior a la fecha de la actividad ('.Carbon::parse($actividad->fecha_actividad)->format('d/m/Y').').',
            'fecha.before_or_equal' => 'La fecha de asistencia no puede ser una fecha futura.',
        ]);

        foreach ($request->asistencias as $ninoId => $datos) {
            Asistencia::updateOrCreate(
                [
                    'actividad_id' => $actividad->id,
                    'nino_id' => $ninoId,
                    'fecha' => $request->fecha,
                ],
                [
                    'estado' => $datos['estado'],
                    'observacion' => $datos['observacion'] ?? null,
                    'registrado_por' => auth()->id(),
                ]
            );
        }

        return redirect()->route('actividades.index')
            ->with('success', 'Asistencia registrada correctamente.');
    }

    public function index(Actividad $actividad)
    {
        $asistencias = Asistencia::with(['nino', 'usuario'])
            ->where('actividad_id', $actividad->id)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('asistencia.index', compact('actividad', 'asistencias'));
    }

    /**
     * Exporta las asistencias filtradas por el período seleccionado.
     */
    public function exportar(Request $request, Actividad $actividad)
    {
        $periodo = $request->get('periodo', 'anterior');
        $nombreActividadClean = str_replace(' ', '_', strtolower($actividad->nombre));
        $nombreArchivo = "asistencias_{$nombreActividadClean}";

        // Verificamos si el período viene en formato año-mes (ej: "2026-07", "2026-06", etc.)
        if (str_contains($periodo, '-')) {
            try {
                // Separamos año y mes de forma segura
                [$anio, $mes] = explode('-', $periodo);

                $inicio = Carbon::createFromDate($anio, $mes, 1)->startOfMonth();
                $fin = Carbon::createFromDate($anio, $mes, 1)->endOfMonth();

                $nombreArchivo .= '_'.$mes.'_'.$anio;
            } catch (\Exception $e) {
                // Fallback por si acaso ocurre algún error en el formato
                $inicio = Carbon::now()->startOfMonth();
                $fin = Carbon::now()->endOfMonth();
            }
        } else {
            switch ($periodo) {
                case 'actual':
                    $inicio = Carbon::now()->startOfMonth();
                    $fin = Carbon::now()->endOfMonth();
                    $nombreArchivo .= '_'.Carbon::now()->format('m_Y');
                    break;

                case 'todo':
                    $inicio = null;
                    $fin = null;
                    $nombreArchivo .= '_historico_completo';
                    break;

                case 'anterior':
                default:
                    $inicio = Carbon::now()->subMonth()->startOfMonth();
                    $fin = Carbon::now()->subMonth()->endOfMonth();
                    $nombreArchivo .= '_mes_anterior_'.Carbon::now()->subMonth()->format('m_Y');
                    break;
            }
        }

        // Pasamos el ID de la actividad y los rangos de fechas a la clase de Exportación
        return Excel::download(
            new AsistenciaExport($actividad->id, $inicio, $fin),
            $nombreArchivo.'.xlsx'
        );
    }
}
