<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Nino;
use App\Models\Asistencia;
use App\Models\Actividad; 
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Reporte de Asistencia Mensual
     */
    /**
     * Reporte de Asistencia Mensual (Ya existente y corregido)
     */
    public function asistenciaMensual(Request $request, $grupoId)
    {
        // 🛠️ AQUÍ ESTÁ EL CAMBIO: Forzamos a que si viene vacío o mal enviado, tome el mes y año real de hoy
        $mes = is_numeric($request->get('mes')) && $request->get('mes') > 0 
            ? (int) $request->get('mes') 
            : now()->month;

        $anio = is_numeric($request->get('anio')) && $request->get('anio') > 0 
            ? (int) $request->get('anio') 
            : now()->year;
        
        $fechaFiltro = Carbon::now()->setDate($anio, $mes, 1)->startOfDay();
        $diasDelMes = $fechaFiltro->daysInMonth; 

        $grupo = Grupo::findOrFail($grupoId);
        $ninos = Nino::with('maestro')
            ->where('grupo_id', $grupoId)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        $ninosIds = $ninos->pluck('id');
        $todasLasAsistencias = Asistencia::whereIn('nino_id', $ninosIds)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->get()
            ->groupBy('nino_id');

        $matrizAsistencias = [];
        foreach ($ninos as $nino) {
            $asistenciasNino = $todasLasAsistencias->get($nino->id, collect())
                ->keyBy(fn($item) => Carbon::parse($item->fecha)->day);

            $totalPresentes = $asistenciasNino->where('estado', 'presente')->count();
            $totalClases = $asistenciasNino->count();
            $porcentaje = $totalClases > 0 ? round(($totalPresentes / $totalClases) * 100) : 100;

            $matrizAsistencias[$nino->id] = [
                'registro' => $asistenciasNino,
                'porcentaje' => $porcentaje
            ];
        }

        $data = [
            'grupo' => $grupo,
            'ninos' => $ninos,
            'diasDelMes' => $diasDelMes,
            // 🛠️ Forzamos el nombre del mes en español con ->locale('es')
            'mesNombre' => ucfirst($fechaFiltro->locale('es')->translatedFormat('F')),
            'anio' => $anio,
            'matriz' => $matrizAsistencias
        ];

        return Pdf::loadView('reportes.asistencia-mensual', $data)
                 ->setPaper('letter', 'landscape')
                 ->stream("Asistencia_{$grupo->nombre}_{$mes}_{$anio}.pdf");
    }

    /**
     * EXPORTAR ACTIVIDADES PDF (Reporte mensual de varias actividades)
     */
    public function exportarActividadesPdf(Request $request)
    {
        $mes = (int) $request->get('mes', now()->month);
        $anio = (int) $request->get('anio', now()->year);

        if ($request->filled('periodo')) {
            $partes = explode('-', $request->input('periodo'));
            if (count($partes) === 2) {
                $anio = (int) $partes[0];
                $mes = (int) $partes[1];
            }
        }

        $inicioMes = Carbon::create($anio, $mes, 1)->startOfMonth();
        $finMes = Carbon::create($anio, $mes, 1)->endOfMonth();

        $actividades = Actividad::with(['asistencias.nino.grupo'])
            ->whereBetween('fecha_actividad', [
                $inicioMes->toDateTimeString(), 
                $finMes->toDateTimeString()
            ])
            ->orderBy('fecha_actividad', 'asc')
            ->get();

        $dataPdf = [
            'actividades' => $actividades,
            // 🛠️ CORREGIDO AQUÍ: Añadido ->locale('es')
            'mesNombre'   => $inicioMes->locale('es')->translatedFormat('F'),
            'anio'        => $anio
        ];

        $pdf = Pdf::loadView('reportes.actividades', $dataPdf)
                 ->setPaper('a4', 'landscape'); 

        return $pdf->stream("Reporte_Actividades_{$anio}_{$mes}.pdf");
    }

    /*
     EXPORTAR ASISTENCIA DE UNA ACTIVIDAD/INTERVENCIÓN ESPECÍFICA
     */
    public function exportarAsistenciaPorActividad(Request $request, $actividadId)
    {
        $actividad = Actividad::findOrFail($actividadId);

        $periodo = $request->get('periodo'); // 'todo', '2026-05', etc.

        $todo = false;
        $mes = null;
        $anio = null;

        if ($periodo === 'todo') {
            $todo = true;
            $mesNombre = 'Histórico completo';
        } elseif ($periodo === 'anterior') {
            $fecha = Carbon::now()->subMonth();
            $mes = $fecha->month;
            $anio = $fecha->year;
            // 🛠️ CORREGIDO AQUÍ: Añadido ->locale('es')
            $mesNombre = $fecha->locale('es')->translatedFormat('F');
        } elseif ($periodo && str_contains($periodo, '-')) {
            try {
                [$anioStr, $mesStr] = explode('-', $periodo);
                $anio = (int) $anioStr;
                $mes = (int) $mesStr;
                // Este ya lo tenías bien con ->locale('es')
                $mesNombre = ucfirst(Carbon::create($anio, $mes, 1)->locale('es')->translatedFormat('F'));
            } catch (\Exception $e) {
                $mes = now()->month;
                $anio = now()->year;
                // 🛠️ CORREGIDO AQUÍ: Añadido ->locale('es')
                $mesNombre = ucfirst(Carbon::now()->locale('es')->translatedFormat('F'));
            }
        } else {
            $mes = (int) $request->get('mes', now()->month);
            $anio = (int) $request->get('anio', now()->year);
            // Este también lo tenías bien con ->locale('es')
            $mesNombre = ucfirst(Carbon::create($anio, $mes, 1)->locale('es')->translatedFormat('F'));
        }

        $query = Asistencia::with(['nino.grupo'])
            ->where('actividad_id', $actividad->id);

        if (!$todo) {
            $query->whereMonth('fecha', $mes)
                  ->whereYear('fecha', $anio);
        }

        $asistencias = $query->orderBy('fecha', 'asc')->get();

        $sesiones = $asistencias->groupBy(function ($item) {
            return Carbon::parse($item->fecha)->format('Y-m-d');
        });

        $totalPresentes = $asistencias->where('estado', 'presente')->count();
        $totalRegistros = $asistencias->count();
        $porcentaje = $totalRegistros > 0 ? round(($totalPresentes / $totalRegistros) * 100) : 0;

        $dataPdf = [
            'actividad'      => $actividad,
            'sesiones'       => $sesiones,
            'totalPresentes' => $totalPresentes,
            'totalRegistros' => $totalRegistros,
            'porcentaje'     => $porcentaje,
            'mesNombre'      => $mesNombre,
            'anio'           => $todo ? null : $anio,
        ];

        $pdf = Pdf::loadView('reportes.asistencia-actividad', $dataPdf)
                  ->setPaper('a4', 'portrait');

        $nombreArchivo = str_replace(' ', '_', strtolower($actividad->nombre));
        $sufijo = $todo ? 'historico' : "{$anio}_{$mes}";

        return $pdf->stream("Asistencia_{$nombreArchivo}_{$sufijo}.pdf");
    }
}