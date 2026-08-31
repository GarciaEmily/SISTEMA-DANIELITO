<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Actividades</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Mis Actividades')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>📚 Mis Actividades</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Visualiza tus tareas asignadas y gestiona el registro de asistencias de tus grupos.
                </p>
            </div>

            <div class="buttons">
                <a href="{{ route('dashboard') }}" class="btn btn-secundario">
                    ← Volver al panel
                </a>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="success-message" style="background:#E8F5E9; color:#2E7D32; padding:15px; border-radius:10px; margin-bottom:20px; font-weight:bold; border-left:5px solid #4CAF50;">
            {{ session('success') }}
        </div>
    @endif

    @if($actividades->count())

        <div class="panel">

            <h2>Actividades asignadas</h2>

            <div class="table-container">

                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($actividades as $actividad)
                            <tr>
                                <td>
                                    <strong>{{ $actividad->nombre }}</strong>
                                </td>

                                <td>
                                    {{ $actividad->descripcion }}
                                </td>

                                <td>
                                    {{ $actividad->fecha_actividad }}
                                </td>

                                <td>
                                    @if($actividad->tipo == 'normal')
                                        <span class="badge badge-normal">Normal</span>
                                    @else
                                        <span class="badge badge-intervencion">Intervención</span>
                                    @endif
                                </td>

                                <td>
                                    @if($actividad->grupos && $actividad->grupos->count())
                                        @foreach($actividad->grupos as $grupo)
                                            <span class="badge badge-normal" style="margin: 2px 0;">
                                                {{ $grupo->nombre }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span style="color:var(--text-light); font-style: italic;">Sin grupo</span>
                                    @endif
                                </td>

                                <td>
                                    @if($actividad->activa)
                                        <span class="badge badge-active">Activa</span>
                                    @else
                                        <span class="badge badge-inactive">Inactiva</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="acciones">

                                        <a href="{{ route('asistencias.create', $actividad->id) }}"
                                           class="btn-action btn-attendance">
                                            ✅ Asistencia
                                        </a>

                                        <a href="{{ route('asistencias.index', $actividad->id) }}"
                                           class="btn-action btn-view">
                                            📋 Ver
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>

    @else

        <div class="alerts">
            <p>📚 No tienes actividades asignadas.</p>
        </div>

    @endif

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>