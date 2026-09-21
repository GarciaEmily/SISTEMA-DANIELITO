<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Registrar Asistencia')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>✅ Registrar asistencia</h2>

                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Marca el estado de asistencia de los niños asignados a esta actividad.
                </p>
            </div>

            <a href="{{ route('actividades.index') }}" class="btn btn-secundario">
                ← Volver a actividades
            </a>

        </div>
    </div>

    <div class="panel" style="margin-bottom:25px;">

        <h2>{{ $actividad->nombre }}</h2>

        <p style="color:var(--text-mid); font-weight:600;">
            <strong>Grupo:</strong>

            <span class="badge badge-info">
                {{ $actividad->grupo->nombre ?? 'Sin grupo' }}
            </span>
        </p>

    </div>

    <div class="panel">

        <form method="POST" action="{{ route('asistencias.store', $actividad->id) }}">
            @csrf

            <div class="form-group" style="margin-bottom:25px;">
                <label>Fecha de asistencia</label>

                <input type="date"
                       name="fecha"
                       value="{{ old('fecha', now()->toDateString()) }}"
                       @if($actividad->fecha_actividad) min="{{ $actividad->fecha_actividad }}" @endif
                       max="{{ now()->toDateString() }}"
                       required>
                <small style="color: var(--text-mid); display: block; margin-top: 5px;">
                    @if($actividad->fecha_actividad)
                        * Solo se permiten fechas entre el {{ \Carbon\Carbon::parse($actividad->fecha_actividad)->format('d/m/Y') }} y hoy.
                    @else
                        * Solo se permiten fechas hasta hoy (esta es una intervención recurrente, sin fecha única).
                    @endif
                </small>
            </div>

            <div class="table-container">

                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Niño</th>
                            <th>Estado</th>
                            <th>Observación</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ninos as $nino)

                            <tr>
                                <td>
                                    <strong>{{ $nino->codigo }}</strong>
                                </td>

                                <td>
                                    {{ $nino->nombres }} {{ $nino->apellidos }}
                                </td>

                                <td>
                                    <select name="asistencias[{{ $nino->id }}][estado]" required>
                                        <option value="presente">✅ Presente</option>
                                        <option value="ausente">❌ Ausente</option>
                                        <option value="justificado">⚠️ Justificado</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="text"
                                           name="asistencias[{{ $nino->id }}][observacion]"
                                           placeholder="Agregar observación...">
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4">
                                    No hay niños asignados a esta actividad.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>

            </div>

            <div class="submit-container">
                <button type="submit" class="btn-guardar">
                    💾 Guardar asistencia
                </button>
            </div>

        </form>

    </div>

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>