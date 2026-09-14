<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Fundación Danielito</title>
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">

</head>

<body>

@section('panel-title', 'Dashboard')

@include('partials.topbar')



<div class="container">

    <h2>Resumen general</h2>

    <div class="cards">

        <div class="card">
            <h3>👦 Niños</h3>
            <p>{{ $totalNinos }}</p>
        </div>

        <div class="card">
            <h3>👥 Grupos</h3>
            <p>{{ $totalGrupos }}</p>
        </div>

        <div class="card">
            <h3>📘 Actividades</h3>
            <p>{{ $totalActividades }}</p>
        </div>

        <div class="card">
            <h3>✅ Asistencias</h3>
            <p>{{ $totalAsistencias }}</p>
        </div>

        <div class="card">
            <h3>⚠️ Vulnerables</h3>
            <p>{{ $ninosVulnerables }}</p>
        </div>

    </div>

    <h2>Accesos rápidos</h2>

    <div class="quick-buttons">

        <a href="{{ route('ninos.index') }}" class="btn btn-verde">
            👦 Ver niños
        </a>

        <a href="{{ route('ninos.create') }}" class="btn btn-naranja">
            ➕ Registrar niño
        </a>
        <div class="btn btn-naranja">
    <a href="{{ route('ninos.importar') }}" class="btn btn-outline-success">
        <i class="fas fa-file-excel me-2"></i> Importar desde Excel
    </a>
    
</div>

        <a href="{{ route('actividades.index') }}" class="btn btn-verde">
            📘 Actividades
        </a>

        <a href="{{ route('actividades.create') }}" class="btn btn-naranja">
            ➕ Crear actividad
        </a>

        @if(Route::has('usuarios.index') && auth()->user()->role->nombre === 'Directora')
            <a href="{{ route('usuarios.index') }}" class="btn btn-secundario">
                👤 Usuarios
            </a>
        @endif

        @if(Route::has('asistencias.reporte'))
            <a href="{{ route('asistencias.reporte') }}" class="btn btn-secundario">
                📊 Reporte asistencia
            </a>
        @endif

    </div>

    <div class="content-grid">

        <div class="panel">

            <h2>Últimas actividades</h2>

            <table>
                <thead>
                    <tr>
                        <th>Actividad</th>
                        <th>Tipo</th>
                        <th>Grupos participantes</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ultimasActividades as $actividad)

                        @php
                            $gruposParticipantes = $actividad->ninos
                                ->pluck('grupo.nombre')
                                ->filter()
                                ->unique()
                                ->values();
                        @endphp

                        <tr>
                            <td>{{ $actividad->nombre }}</td>
                            <td>{{ ucfirst($actividad->tipo) }}</td>
                            <td>
                                @if($gruposParticipantes->count() > 0)
                                    {{ $gruposParticipantes->join(', ') }}
                                @else
                                    {{ $actividad->grupo->nombre ?? 'Sin grupo' }}
                                @endif
                            </td>
                            <td>{{ $actividad->fecha_actividad }}</td>
                            <td>{{ $actividad->activa ? 'Activa' : 'Inactiva' }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5">No hay actividades registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="panel">

            <h2>Resumen de asistencia por grupo</h2>

            <table>
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Total</th>
                        <th>Presentes</th>
                        <th>Ausentes</th>
                        <th>Justificados</th>
                        <th>% Asistencia</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($asistenciaPorGrupo as $grupo)

                        <tr>
                            <td>{{ $grupo->grupo }}</td>
                            <td>{{ $grupo->total }}</td>
                            <td>{{ $grupo->presentes }}</td>
                            <td>{{ $grupo->ausentes }}</td>
                            <td>{{ $grupo->justificados }}</td>
                            <td>
                                {{ $grupo->total > 0 ? round(($grupo->presentes / $grupo->total) * 100, 1) : 0 }}%
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6">No hay asistencias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <h3>Porcentaje de asistencia: {{ $porcentajeAsistencia }}%</h3>

        </div>

    </div>

    <div class="panel">

    <details>

        <summary class="summary-vulnerables">
            ⚠️ Niños en condición vulnerable
            ({{ $listaVulnerables->count() }})
        </summary>

        @if($listaVulnerables->count())

            <table>

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($listaVulnerables as $nino)

                    <tr>

                        <td>{{ $nino->codigo }}</td>

                        <td>
                            {{ $nino->nombres }}
                            {{ $nino->apellidos }}
                        </td>

                        <td>
                            {{ $nino->grupo->nombre ?? 'Sin grupo' }}
                        </td>

                        <td>
                            <a href="{{ route('ninos.show',$nino->id) }}"
                               class="btn btn-naranja">
                                👁 Ver ficha
                            </a>
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="success-box">
                ✅ No existen niños vulnerables registrados.
            </div>

        @endif

    </details>

</div>

<div class="panel">

    <details>

        <summary class="summary-vulnerables">
            🎂 Cumpleaños del mes
            ({{ $cumpleanosCercanos->count() }})
        </summary>

        @if($cumpleanosCercanos->count())

            <table>

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                        <th>Cumpleaños</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($cumpleanosCercanos as $nino)

                    <tr>

                        <td>{{ $nino->codigo }}</td>

                        <td>
                            {{ $nino->nombres }}
                            {{ $nino->apellidos }}
                        </td>

                        <td>
                            {{ $nino->grupo->nombre ?? 'Sin grupo' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($nino->fecha_nacimiento)->format('d/m') }}
                        </td>

                        <td>
                            <a href="{{ route('ninos.show',$nino->id) }}"
                               class="btn btn-naranja">
                                👁 Ver ficha
                            </a>
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="success-box">
                🎉 No hay cumpleaños este mes.
            </div>

        @endif

    </details>

</div>

</div>

</body>
</html>