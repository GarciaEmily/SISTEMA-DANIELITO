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

        @if(Route::has('grupos.index'))
            <a href="{{ route('grupos.index') }}" class="btn btn-secundario">
                👥 Grupos
            </a>
        @endif

    </div>

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

    @include('partials.dashboard-vulnerables')

    @include('partials.dashboard-cumpleanos')

</div>

</body>
</html>
