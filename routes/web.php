<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NinoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ImportacionNinosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GrupoController;
use App\Models\Nino;
use App\Models\Grupo;
use App\Models\Actividad;
use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role->nombre) {
        'Directora' => redirect('/directora'),
        'Administrador' => redirect('/admin'),
        'Maestro' => redirect('/maestro'),
        default => abort(403),
    };
})->middleware(['auth'])->name('dashboard');

Route::get('/directora', function () {
    $presentes = Asistencia::where('estado', 'presente')->count();
    $ausentes = Asistencia::where('estado', 'ausente')->count();
    $justificados = Asistencia::where('estado', 'justificado')->count();
    $totalAsistencias = Asistencia::count();
    $asistenciaPorGrupo = Asistencia::selectRaw('
        grupos.nombre as grupo,
        COUNT(*) as total,
        SUM(CASE WHEN asistencias.estado = "presente" THEN 1 ELSE 0 END) as presentes,
        SUM(CASE WHEN asistencias.estado = "ausente" THEN 1 ELSE 0 END) as ausentes,
        SUM(CASE WHEN asistencias.estado = "justificado" THEN 1 ELSE 0 END) as justificados
    ')
    ->join('ninos', 'asistencias.nino_id', '=', 'ninos.id')
    ->join('grupos', 'ninos.grupo_id', '=', 'grupos.id')
    ->groupBy('grupos.nombre')
    ->get();

    $ultimasActividades = Actividad::with(['grupo', 'ninos.grupo'])
        ->orderBy('fecha_actividad', 'desc')
        ->take(5)
        ->get();

    $listaVulnerables = Nino::with('grupo')
        ->where('vulnerable', true)
        ->orderBy('apellidos')
        ->orderBy('nombres')
        ->get();

    $cumpleanosCercanos = Nino::with('grupo')
        ->cumpleanosDelMes()
        ->get()
        ->sortBy(function ($nino) {
            return Carbon::parse($nino->fecha_nacimiento)->day;
        })
        ->values();

    return view('directora', [
        'totalNinos' => Nino::count(),
        'totalGrupos' => Grupo::count(),
        'totalActividades' => Actividad::count(),
        'totalAsistencias' => $totalAsistencias,
        'ninosVulnerables' => Nino::where('vulnerable', true)->count(),
        'listaVulnerables' => $listaVulnerables,
        'cumpleanosCercanos' => $cumpleanosCercanos,
        'presentes' => $presentes,
        'ausentes' => $ausentes,
        'justificados' => $justificados,
        'porcentajeAsistencia' => $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100, 1) : 0,
        'ultimasActividades' => $ultimasActividades,
        'asistenciaPorGrupo' => $asistenciaPorGrupo,
    ]);
})->middleware(['auth', 'role:Directora']);


Route::get('/admin', function () {
    $presentes = Asistencia::where('estado', 'presente')->count();
    $ausentes = Asistencia::where('estado', 'ausente')->count();
    $justificados = Asistencia::where('estado', 'justificado')->count();
    $totalAsistencias = Asistencia::count();
    $asistenciaPorGrupo = Asistencia::selectRaw('
        grupos.nombre as grupo,
        COUNT(*) as total,
        SUM(CASE WHEN asistencias.estado = "presente" THEN 1 ELSE 0 END) as presentes,
        SUM(CASE WHEN asistencias.estado = "ausente" THEN 1 ELSE 0 END) as ausentes,
        SUM(CASE WHEN asistencias.estado = "justificado" THEN 1 ELSE 0 END) as justificados
    ')
    ->join('ninos', 'asistencias.nino_id', '=', 'ninos.id')
    ->join('grupos', 'ninos.grupo_id', '=', 'grupos.id')
    ->groupBy('grupos.nombre')
    ->get();

    $ultimasActividades = Actividad::with(['grupo', 'ninos.grupo'])
        ->orderBy('fecha_actividad', 'desc')
        ->take(5)
        ->get();
    $listaVulnerables = Nino::where('vulnerable', true)->get();

    $cumpleanosCercanos = Nino::with('grupo')
        ->cumpleanosDelMes()
        ->get()
        ->sortBy(function ($nino) {
            return Carbon::parse($nino->fecha_nacimiento)->day;
        })
        ->values();

    return view('admin', [
        'totalNinos' => Nino::count(),
        'totalGrupos' => Grupo::count(),
        'totalActividades' => Actividad::count(),
        'totalAsistencias' => $totalAsistencias,
        'ninosVulnerables' => Nino::where('vulnerable', true)->count(),
        'listaVulnerables' => $listaVulnerables,
        'cumpleanosCercanos' => $cumpleanosCercanos,
        'presentes' => $presentes,
        'ausentes' => $ausentes,
        'justificados' => $justificados,
        'porcentajeAsistencia' => $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100, 1) : 0,
        'ultimasActividades' => $ultimasActividades,
        'asistenciaPorGrupo' => $asistenciaPorGrupo,
    ]);
})->middleware(['auth', 'role:Administrador']);

Route::get('/maestro', function () {
    $user = auth()->user();

    $hoy = Carbon::now();

    $misNinosQuery = Nino::where('maestro_id', $user->id);

    $cumpleanosCercanos = Nino::where('maestro_id', $user->id)
        ->whereNotNull('fecha_nacimiento')
        ->get()
        ->filter(function ($nino) use ($hoy) {
            $cumple = Carbon::parse($nino->fecha_nacimiento)->year($hoy->year);

            if ($cumple->isPast()) {
                $cumple->addYear();
            }

            return $hoy->diffInDays($cumple) <= 30;
        })
        ->sortBy(function ($nino) use ($hoy) {
            $cumple = Carbon::parse($nino->fecha_nacimiento)->year($hoy->year);

            if ($cumple->isPast()) {
                $cumple->addYear();
            }

            return $hoy->diffInDays($cumple);
        })
        ->take(5);

    $misMasAsistentes = Asistencia::select(
            'nino_id',
            DB::raw('COUNT(*) as total_asistencias')
        )
        ->where('estado', 'presente')
        ->whereHas('nino', function ($q) use ($user) {
            $q->where('maestro_id', $user->id);
        })
        ->with('nino')
        ->groupBy('nino_id')
        ->orderByDesc('total_asistencias')
        ->take(5)
        ->get();

    $miAsistenciaMes = Asistencia::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN estado = "presente" THEN 1 ELSE 0 END) as presentes,
            SUM(CASE WHEN estado = "ausente" THEN 1 ELSE 0 END) as ausentes,
            SUM(CASE WHEN estado = "justificado" THEN 1 ELSE 0 END) as justificados
        ')
        ->whereMonth('fecha', now()->month)
        ->whereYear('fecha', now()->year)
        ->whereHas('nino', function ($q) use ($user) {
            $q->where('maestro_id', $user->id);
        })
        ->first();

    return view('maestro', [
        'totalMisNinos' => $misNinosQuery->count(),

        'totalMisActividades' => Actividad::whereHas('grupos.ninos', function ($q) use ($user) {
            $q->where('maestro_id', $user->id);
        })->count(),

        'totalMisAsistencias' => Asistencia::whereHas('nino', function ($q) use ($user) {
            $q->where('maestro_id', $user->id);
        })->count(),

        'cumpleanosCercanos' => $cumpleanosCercanos,
        'misMasAsistentes' => $misMasAsistentes,
        'miAsistenciaMes' => $miAsistenciaMes,
    ]);
})->middleware(['auth', 'role:Maestro']);

/*
|--------------------------------------------------------------------------
| Rutas de niños
|--------------------------------------------------------------------------
*/

Route::get('/ninos/importar', [ImportacionNinosController::class, 'index'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.importar');

Route::get('/ninos/plantilla', [ImportacionNinosController::class, 'descargarPlantilla'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.plantilla');

Route::post('/ninos/importar', [ImportacionNinosController::class, 'store'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.importar.store');

Route::get('/ninos', [NinoController::class, 'index'])
    ->middleware(['auth'])
    ->name('ninos.index');

Route::get('/ninos/create', [NinoController::class, 'create'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.create');

Route::post('/ninos', [NinoController::class, 'store'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.store');

Route::get('/ninos/{nino}/edit', [NinoController::class, 'edit'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.edit');

Route::put('/ninos/{nino}', [NinoController::class, 'update'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.update');

Route::delete('/ninos/{nino}', [NinoController::class, 'destroy'])
    ->middleware(['auth', 'admin.directora'])
    ->name('ninos.destroy');

Route::get('/ninos/{nino}', [NinoController::class, 'show'])
    ->middleware(['auth'])
    ->name('ninos.show');

/*
|--------------------------------------------------------------------------
| Rutas de actividades
|--------------------------------------------------------------------------
*/

Route::get('/actividades', [ActividadController::class, 'index'])
    ->middleware(['auth'])
    ->name('actividades.index');

Route::get('/actividades/create', [ActividadController::class, 'create'])
    ->middleware(['auth', 'admin.directora'])
    ->name('actividades.create');

Route::post('/actividades', [ActividadController::class, 'store'])
    ->middleware(['auth', 'admin.directora'])
    ->name('actividades.store');

Route::get('/actividades/{actividad}/edit', [ActividadController::class, 'edit'])
    ->middleware(['auth', 'admin.directora'])
    ->name('actividades.edit');

Route::put('/actividades/{actividad}', [ActividadController::class, 'update'])
    ->middleware(['auth', 'admin.directora'])
    ->name('actividades.update');

Route::delete('/actividades/{actividad}', [ActividadController::class, 'destroy'])
    ->middleware(['auth', 'admin.directora'])
    ->name('actividades.destroy');

Route::get('/actividades/{actividad}/asignar-ninos', [ActividadController::class, 'asignarNinos'])
    ->middleware(['auth', 'admin.directora'])
    ->name('actividades.asignar_ninos');

Route::post('/actividades/{actividad}/guardar-ninos', [ActividadController::class, 'guardarNinos'])
    ->middleware(['auth', 'admin.directora'])
    ->name('actividades.guardar_ninos');

Route::get('/actividades/{actividad}/asistencia', [AsistenciaController::class, 'create'])
    ->middleware(['auth'])
    ->name('asistencias.create');

Route::post('/actividades/{actividad}/asistencia', [AsistenciaController::class, 'store'])
    ->middleware(['auth'])
    ->name('asistencias.store');

Route::get('/actividades/{actividad}/asistencias', [AsistenciaController::class, 'index'])
    ->middleware(['auth'])
    ->name('asistencias.index');

/*
|--------------------------------------------------------------------------
| Rutas de grupos (Directora y Administrador)
|--------------------------------------------------------------------------
*/

Route::get('/grupos', [GrupoController::class, 'index'])
    ->middleware(['auth', 'admin.directora'])
    ->name('grupos.index');

Route::get('/grupos/create', [GrupoController::class, 'create'])
    ->middleware(['auth', 'admin.directora'])
    ->name('grupos.create');

Route::post('/grupos', [GrupoController::class, 'store'])
    ->middleware(['auth', 'admin.directora'])
    ->name('grupos.store');

Route::get('/grupos/{grupo}/edit', [GrupoController::class, 'edit'])
    ->middleware(['auth', 'admin.directora'])
    ->name('grupos.edit');

Route::put('/grupos/{grupo}', [GrupoController::class, 'update'])
    ->middleware(['auth', 'admin.directora'])
    ->name('grupos.update');

Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])
    ->middleware(['auth', 'admin.directora'])
    ->name('grupos.destroy');

/*
|--------------------------------------------------------------------------
| Rutas de reportes
|--------------------------------------------------------------------------
*/

Route::get('/reportes/asistencia-grupo/{grupo}', [ReporteController::class, 'asistenciaMensual'])
    ->middleware(['auth', 'admin.directora'])
    ->name('reportes.asistencia.grupo');

Route::get('/reportes/actividades/pdf', [ReporteController::class, 'exportarActividadesPdf'])
    ->middleware(['auth', 'admin.directora'])
    ->name('reportes.actividades.pdf');

Route::get('/reportes/asistencia-actividad/{actividad}', [ReporteController::class, 'exportarAsistenciaPorActividad'])
    ->middleware(['auth', 'admin.directora'])
    ->name('reportes.asistencia_actividad.pdf');

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas de perfil ("Mi cuenta")
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
/*
|--------------------------------------------------------------------------
| Rutas de usuarios (solo Directora)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Directora'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});