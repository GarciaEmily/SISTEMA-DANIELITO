<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asistencias registradas</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>
@section('panel-title', 'Asistencias')

@include('partials.topbar')

<div class="container">

   <div class="quick-actions">

    <div style="display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;">

        <div>
            <h2>📋 Asistencias registradas</h2>

            <p style="margin:0;color:var(--text-mid);font-weight:500;">
                Historial de asistencia de la actividad.
            </p>
        </div>

        <a href="{{ route('actividades.index') }}"
           class="btn btn-secundario">

            ← Volver a actividades

        </a>

    </div>

</div>

<div class="panel" style="margin-bottom:25px;">

    <h2>{{ $actividad->nombre }}</h2>

    <p style="color:var(--text-mid);font-weight:600;">

        <strong>Grupo:</strong>

        <span class="badge badge-info">
            {{ $actividad->grupo->nombre ?? 'Sin grupo' }}
        </span>

    </p>

</div>

    @php

        $fechas = $asistencias
            ->pluck('fecha')
            ->unique()
            ->sort()
            ->values();

        $ninos = $asistencias
            ->groupBy('nino_id');

    @endphp

    @if($ninos->count())

        <div class="panel">

            <table>

                <thead>

                    <tr>

                        <th>Código</th>

                        <th>Niño</th>

                        @foreach($fechas as $fecha)

                            <th>{{ $fecha }}</th>

                        @endforeach

                    </tr>

                </thead>

                <tbody>

                    @foreach($ninos as $ninoId => $registros)

                        @php
                            $nino = $registros->first()->nino;
                        @endphp

                        <tr>

                            <td class="codigo">

                                {{ $nino->codigo ?? '' }}

                            </td>

                            <td class="nino">

                                {{ $nino->nombres ?? '' }}
                                {{ $nino->apellidos ?? '' }}

                            </td>

                            @foreach($fechas as $fecha)

                                @php

                                    $asistencia = $registros
                                        ->where('fecha', $fecha)
                                        ->first();

                                @endphp

                                <td>

                                    @if($asistencia)

                                        <span class="badge {{ $asistencia->estado }}">

                                            {{ ucfirst($asistencia->estado) }}

                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>

                            @endforeach

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="alerts">

            No hay asistencias registradas para esta actividad.

        </div>

    @endif

</div>
<p class="footer-brand">
    © Fundación Danielito — Warnes, Santa Cruz
</p>

</body>
</html>